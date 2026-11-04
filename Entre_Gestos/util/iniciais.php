<?php
/**
 * Iniciais para o avatar (no lugar da foto).
 * "João Pedro"            -> "JP"
 * "Maria da Silva"        -> "MS"  (primeira + última palavra)
 * "Aluno"                 -> "A"
 * sem nome, usa o e-mail: "joao.pedro@x.com" -> "JP"
 */
if (!function_exists('iniciais')) {
    function iniciais(?string $nome, ?string $email = ''): string
    {
        $sub  = function_exists('mb_substr')      ? 'mb_substr'      : 'substr';
        $caps = function_exists('mb_strtoupper')  ? 'mb_strtoupper'  : 'strtoupper';

        $nome = trim((string) $nome);
        if ($nome !== '') {
            $partes = preg_split('/\s+/u', $nome);
        } else {
            // sem nome: usa a parte antes do @ do e-mail, separando por . _ - +
            $local  = explode('@', (string) $email)[0];
            $partes = preg_split('/[._\-+\s]+/u', trim($local));
        }

        $partes = array_values(array_filter($partes, fn($p) => $p !== ''));
        if (!$partes) return '?';

        $primeira = $sub($partes[0], 0, 1);
        $ultima   = count($partes) > 1 ? $sub($partes[count($partes) - 1], 0, 1) : '';

        return $caps($primeira . $ultima);
    }
}
