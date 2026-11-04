<?php
/**
 * Funções para limpar/validar os dados extras do usuário
 * (apelido, telefone, nascimento, gênero).
 * Usadas no cadastro, no perfil e nos formulários do admin.
 */

if (!function_exists('perfil_generos')) {
    /** Opções de gênero (cada valor cabe em varchar(16)). */
    function perfil_generos(): array
    {
        return ['Feminino', 'Masculino', 'Não-binário', 'Outro', 'Não informar'];
    }
}

if (!function_exists('perfil_limpar_texto')) {
    /** Tira espaços, corta no tamanho da coluna e devolve null se ficar vazio. */
    function perfil_limpar_texto($valor, int $max): ?string
    {
        $valor = trim((string) $valor);
        if ($valor === '') {
            return null;
        }
        return function_exists('mb_substr') ? mb_substr($valor, 0, $max) : substr($valor, 0, $max);
    }
}

if (!function_exists('perfil_limpar_telefone')) {
    /**
     * Aceita DDD + 8 ou 9 dígitos (com ou sem máscara) e devolve "(45) 99999-9999".
     * Devolve null se estiver vazio ou inválido.
     */
    function perfil_limpar_telefone($valor): ?string
    {
        $n = preg_replace('/\D+/', '', (string) $valor);
        if (strlen($n) === 11) {
            return sprintf('(%s) %s-%s', substr($n, 0, 2), substr($n, 2, 5), substr($n, 7));
        }
        if (strlen($n) === 10) {
            return sprintf('(%s) %s-%s', substr($n, 0, 2), substr($n, 2, 4), substr($n, 6));
        }
        return null;
    }
}

if (!function_exists('perfil_limpar_data')) {
    /** Aceita só AAAA-MM-DD válido, que não seja no futuro; senão null. */
    function perfil_limpar_data($valor): ?string
    {
        $valor = trim((string) $valor);
        $d = DateTime::createFromFormat('Y-m-d', $valor);
        if (!$d || $d->format('Y-m-d') !== $valor || $d > new DateTime('today') || $d < new DateTime('1900-01-01')) {
            return null;
        }
        return $valor;
    }
}

if (!function_exists('perfil_limpar_genero')) {
    /** Só aceita uma das opções da lista; senão null. */
    function perfil_limpar_genero($valor): ?string
    {
        $valor = trim((string) $valor);
        return in_array($valor, perfil_generos(), true) ? $valor : null;
    }
}

if (!function_exists('perfil_validar_extras')) {
    /**
     * Valida os 4 campos extras vindos de um formulário.
     *
     * @param array $in           normalmente $_POST
     * @param bool  $obrigatorios true no cadastro: telefone, nascimento e gênero são exigidos
     *                            (apelido é sempre opcional)
     * @return array [extras limpos, mensagem de erro ('' se estiver tudo certo)]
     */
    function perfil_validar_extras(array $in, bool $obrigatorios): array
    {
        $extras = [
            'apelido'         => perfil_limpar_texto($in['apelido'] ?? '', 16),
            'numero_telefone' => perfil_limpar_telefone($in['numero_telefone'] ?? ''),
            'data_nascimento' => perfil_limpar_data($in['data_nascimento'] ?? ''),
            'genero'          => perfil_limpar_genero($in['genero'] ?? ''),
        ];

        $erro = '';
        $temTel  = trim((string) ($in['numero_telefone'] ?? '')) !== '';
        $temNasc = trim((string) ($in['data_nascimento'] ?? '')) !== '';
        $temGen  = trim((string) ($in['genero'] ?? '')) !== '';

        if ($temTel && $extras['numero_telefone'] === null) {
            $erro = 'Telefone inválido. Use o DDD e o número, ex.: (45) 99999-9999.';
        } elseif ($temNasc && $extras['data_nascimento'] === null) {
            $erro = 'Data de nascimento inválida.';
        } elseif ($temGen && $extras['genero'] === null) {
            $erro = 'Escolha um gênero da lista.';
        } elseif ($obrigatorios) {
            if ($extras['numero_telefone'] === null)      $erro = 'Informe seu telefone.';
            elseif ($extras['data_nascimento'] === null)  $erro = 'Informe sua data de nascimento.';
            elseif ($extras['genero'] === null)           $erro = 'Escolha seu gênero.';
        }

        return [$extras, $erro];
    }
}
