<?php
/**
 * Ajudas para vídeos do YouTube (alfabeto).
 * - youtube_thumb(): endereço da miniatura do vídeo (o navegador baixa direto do YouTube).
 * - youtube_canal(): nome e link do canal do vídeo, via oEmbed (não precisa de chave de API).
 */

// canal usado quando não dá para descobrir o canal de um vídeo
const YT_CANAL_PADRAO_NOME = 'Saber Mais';
const YT_CANAL_PADRAO_URL  = 'https://www.youtube.com/@incluirtecnologia';

if (!function_exists('youtube_thumb')) {
    function youtube_thumb(string $videoId): string
    {
        // mqdefault = 320x180, já no formato 16:9 (sem faixas pretas)
        return 'https://i.ytimg.com/vi/' . rawurlencode($videoId) . '/mqdefault.jpg';
    }
}

if (!function_exists('youtube_canal')) {
    /**
     * @return array|null ['nome' => ..., 'url' => ...] ou null se não conseguiu consultar
     */
    function youtube_canal(string $videoId): ?array
    {
        $api = 'https://www.youtube.com/oembed?format=json&url='
             . rawurlencode('https://www.youtube.com/watch?v=' . $videoId);

        $json = false;
        if (function_exists('curl_init')) {
            $ch = curl_init($api);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 3,
                CURLOPT_CONNECTTIMEOUT => 2,
                CURLOPT_FOLLOWLOCATION => true,
            ]);
            $json = curl_exec($ch);
            curl_close($ch);
        } else {
            $json = @file_get_contents($api, false, stream_context_create(['http' => ['timeout' => 3]]));
        }

        $dados = $json ? json_decode($json, true) : null;
        if (!is_array($dados) || empty($dados['author_name']) || empty($dados['author_url'])) {
            return null;
        }
        // só aceita links do YouTube
        if (!preg_match('#^https://(www\.)?youtube\.com/#', $dados['author_url'])) {
            return null;
        }
        return ['nome' => (string) $dados['author_name'], 'url' => (string) $dados['author_url']];
    }
}

if (!function_exists('youtube_completar_canais')) {
    /**
     * Para cada letra sem canal salvo, descobre o canal uma vez e guarda no banco.
     * Se o YouTube não responder, usa o canal padrão (sem gravar) e só tenta de novo daqui a 10 min.
     *
     * @param Alfabeto[] $letras
     */
    function youtube_completar_canais(array $letras, AlfabetoDAO $dao): void
    {
        $podeTentar = (($_SESSION['yt_canal_falhou_em'] ?? 0) + 600) < time();

        foreach ($letras as $item) {
            if ($item->getCanalNome() && $item->getCanalUrl()) continue;
            if (!$item->getVideoId()) continue;

            if ($podeTentar) {
                $canal = youtube_canal($item->getVideoId());
                if ($canal) {
                    $item->setCanalNome($canal['nome'])->setCanalUrl($canal['url']);
                    $dao->salvarCanal((int) $item->getId(), $canal['nome'], $canal['url']);
                    continue;
                }
                $podeTentar = false; // falhou: não perde tempo com as próximas
                $_SESSION['yt_canal_falhou_em'] = time();
            }

            $item->setCanalNome(YT_CANAL_PADRAO_NOME)->setCanalUrl(YT_CANAL_PADRAO_URL);
        }
    }
}
