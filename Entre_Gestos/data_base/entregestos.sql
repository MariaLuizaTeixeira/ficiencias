CREATE DATABASE IF NOT EXISTS entregestos;
USE entregestos;

-- ==========================================
-- REMOVER TABELAS EXISTENTES
-- ==========================================

DROP TABLE IF EXISTS respostas_multipla_escolha;
DROP TABLE IF EXISTS respostas_associacao;
DROP TABLE IF EXISTS respostas_abertas;
DROP TABLE IF EXISTS respostas;
DROP TABLE IF EXISTS progresso_questao;
DROP TABLE IF EXISTS progresso_licoes;
DROP TABLE IF EXISTS pares_associativos;
DROP TABLE IF EXISTS opcoes_questao;
DROP TABLE IF EXISTS itens_associativos;
DROP TABLE IF EXISTS questoes_multipla_escolha;
DROP TABLE IF EXISTS questoes_associativas;
DROP TABLE IF EXISTS questoes_abertas;
DROP TABLE IF EXISTS questoes;
DROP TABLE IF EXISTS licoes;
DROP TABLE IF EXISTS alfabeto;
DROP TABLE IF EXISTS alunos;
DROP TABLE IF EXISTS secoes;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS tipos_questao;
DROP TABLE IF EXISTS cursos;


-- ==========================================
-- TABELAS
-- ==========================================

CREATE TABLE cursos (
    id bigint NOT NULL AUTO_INCREMENT,
    nome varchar(64) NOT NULL,
    descricao text,
    PRIMARY KEY (id)
);

CREATE TABLE tipos_questao (
    nome varchar(32) NOT NULL,
    PRIMARY KEY (nome)
);

CREATE TABLE usuarios (
    id bigint NOT NULL AUTO_INCREMENT,
    nome_completo varchar(64) NOT NULL,
    email varchar(64) NOT NULL,
    senha_hash varchar(512) NOT NULL,
    criado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    email_verificado tinyint(1) NOT NULL DEFAULT 0,
    perfil varchar(16) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (email)
);

CREATE TABLE secoes (
    id bigint NOT NULL AUTO_INCREMENT,
    curso_id bigint NOT NULL,
    titulo varchar(64) NOT NULL,
    ordem int NOT NULL,
    descricao text,
    PRIMARY KEY (id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
);

CREATE TABLE alunos (
    id bigint NOT NULL AUTO_INCREMENT,
    id_usuario bigint NOT NULL,
    vidas bigint NOT NULL,
    sequencia bigint NOT NULL,
    data_nascimento date DEFAULT NULL,
    apelido varchar(16) DEFAULT NULL,
    genero varchar(16) DEFAULT NULL,
    numero_telefone varchar(15) DEFAULT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE alfabeto (
    id bigint NOT NULL AUTO_INCREMENT,
    letra CHAR(1) 
    CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    video_id varchar(20) NULL,
    ordem int NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (letra)
);



CREATE TABLE licoes (
    id bigint NOT NULL AUTO_INCREMENT,
    secao_id bigint NOT NULL,
    titulo varchar(64) NOT NULL,
    ordem int NOT NULL,
    esta_bloqueada int NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (secao_id) REFERENCES secoes(id) ON DELETE CASCADE
);

CREATE TABLE questoes (
    id bigint NOT NULL AUTO_INCREMENT,
    titulo varchar(150) NOT NULL DEFAULT '',
    enunciado text NOT NULL,
    tipo varchar(32) NOT NULL,
    licao_id bigint DEFAULT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (tipo) REFERENCES tipos_questao(nome),
    FOREIGN KEY (licao_id) REFERENCES licoes(id) ON DELETE CASCADE
);
ALTER TABLE questoes ADD COLUMN titulo varchar(150) NOT NULL DEFAULT '' AFTER id;

CREATE TABLE questoes_abertas (
    id bigint NOT NULL,
    resposta_correta text NOT NULL,
    conteudo text NOT NULL,
    tipo_conteudo varchar(16) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id) REFERENCES questoes(id) ON DELETE CASCADE
);

CREATE TABLE questoes_associativas (
    id bigint NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id) REFERENCES questoes(id) ON DELETE CASCADE
);

CREATE TABLE questoes_multipla_escolha (
    id bigint NOT NULL,
    conteudo text NOT NULL,
    tipo_conteudo varchar(16) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id) REFERENCES questoes(id) ON DELETE CASCADE
);

CREATE TABLE itens_associativos (
    id bigint NOT NULL AUTO_INCREMENT,
    conteudo text NOT NULL,
    tipo_conteudo varchar(16) NOT NULL,
    coluna char(1) NOT NULL,
    questao_id bigint NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (questao_id)
        REFERENCES questoes_associativas(id)
        ON DELETE CASCADE
);

CREATE TABLE opcoes_questao (
    id bigint NOT NULL AUTO_INCREMENT,
    questao_id bigint NOT NULL,
    conteudo text NOT NULL,
    tipo_conteudo varchar(16) NOT NULL,
    esta_correta tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (questao_id)
        REFERENCES questoes_multipla_escolha(id)
        ON DELETE CASCADE
);

CREATE TABLE pares_associativos (
    id bigint NOT NULL AUTO_INCREMENT,
    item_esquerdo_id bigint NOT NULL,
    item_direito_id bigint NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (item_esquerdo_id, item_direito_id),
    FOREIGN KEY (item_esquerdo_id)
        REFERENCES itens_associativos(id)
        ON DELETE CASCADE,
    FOREIGN KEY (item_direito_id)
        REFERENCES itens_associativos(id)
        ON DELETE CASCADE
);

CREATE TABLE progresso_licoes (
    id bigint NOT NULL AUTO_INCREMENT,
    aluno_id bigint NOT NULL,
    licao_id bigint NOT NULL,
    completada tinyint(1) NOT NULL DEFAULT 0,
    completado_em timestamp NULL DEFAULT NULL,
    acertou tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (licao_id)
        REFERENCES licoes(id)
        ON DELETE CASCADE
);

CREATE TABLE progresso_questao (
    aluno_id bigint NOT NULL,
    questao_id bigint NOT NULL,
    tentativas int NOT NULL DEFAULT 0,
    esta_correta tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (aluno_id, questao_id),
    FOREIGN KEY (questao_id)
        REFERENCES questoes(id)
        ON DELETE CASCADE
);

CREATE TABLE respostas (
    id bigint NOT NULL AUTO_INCREMENT,
    questao_id bigint NOT NULL,
    aluno_id bigint NOT NULL,
    criado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    esta_correta tinyint(1) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (questao_id)
        REFERENCES questoes(id)
        ON DELETE CASCADE
);

CREATE TABLE respostas_abertas (
    id bigint NOT NULL,
    resposta_texto text NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id)
        REFERENCES respostas(id)
        ON DELETE CASCADE
);

CREATE TABLE respostas_associacao (
    id bigint NOT NULL AUTO_INCREMENT,
    item_esquerdo_id bigint NOT NULL,
    item_direito_id bigint NOT NULL,
    resposta_id bigint NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (item_esquerdo_id)
        REFERENCES itens_associativos(id),
    FOREIGN KEY (item_direito_id)
        REFERENCES itens_associativos(id),
    FOREIGN KEY (resposta_id)
        REFERENCES respostas(id)
        ON DELETE CASCADE
);

CREATE TABLE respostas_multipla_escolha (
    id bigint NOT NULL,
    opcao_selecionada_id bigint NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (opcao_selecionada_id)
        REFERENCES opcoes_questao(id),
    FOREIGN KEY (id)
        REFERENCES respostas(id)
        ON DELETE CASCADE
);


-- ==========================================
-- DADOS
-- ==========================================

INSERT INTO cursos VALUES
(1, 'LIBRAS básico', 'Curso de LIBRAS para iniciantes do 0.');


INSERT INTO tipos_questao VALUES
('aberta'),
('associacao'),
('multipla_escolha');


INSERT INTO usuarios VALUES
(1, 'Aluno', 'aluno@entregestos.com',
 '$2y$12$EbGTg1FUQzB.VMGntOV2c.7I5EZevsWR8rm9zIXgctPQrddUOYqYu',
 '2026-08-16 13:04:22',
 '2026-08-28 16:49:42',
 1,
 'aluno'),

(2, 'Administrador', 'admin@entregestos.com',
 '$2y$12$nSr2kqdBawTea9N8aLaEtOt.J0yq913SPIm27MQ5lIN5nycjmC2rC',
 '2026-08-26 02:28:03',
 '2026-08-28 16:49:55',
 1,
 'administrador'),

(16, 'Brenda Melissa', 'brenda125@gmail.com',
 '$2y$12$G4hW29viMQFqCyb5cuqpPuYYZ91ui/wkhpkjbmok48wGjpyTP6iTm',
 '2026-09-04 12:29:05',
 '2026-09-04 12:29:05',
 1,
 'aluno');


INSERT INTO secoes VALUES
(1, 1, 'Introdução à LIBRAS', 1,
 'Aprenda o básico do alfabeto e saudações essenciais.'),

(2, 1, 'O Dia a Dia em LIBRAS', 2,
 'Vocabulário essencial para o dia a dia, como cores e comida.'),

(3, 1, 'Tarefa de Exemplo 1', 6,
 'geral');


INSERT INTO alunos VALUES
(1, 0, 5, 5, '1989-08-16', '', NULL, NULL),
(2, 11, 5, 0, NULL, NULL, NULL, NULL),
(3, 13, 5, 0, NULL, NULL, NULL, NULL),
(4, 14, 5, 0, NULL, NULL, NULL, NULL),
(6, 16, 5, 0, NULL, NULL, NULL, NULL);


-- ==========================================
-- ALFABETO LIBRAS
-- ==========================================
INSERT INTO alfabeto (letra, video_id, ordem) VALUES
('A', 'JSBlCLlnOjg', 1),
('B', 'gQUFF0w_4WA', 2),
('C', 'nf9gBzoUI68', 3),
('Ç', '6669YqT9uQA', 4),
('D', 'W6G5_iJHO4s', 5),
('E', 'yS-PGhIp9wc', 6),
('F', 'ngG5Eih07GM', 7),
('G', '142vjBvDT2Y', 8),
('H', 'GmCu1WWKzXk', 9),
('I', 'HsF0pWCZKWo', 10),
('J', 'I2vSibIAsIE', 11),
('K', '-V1yQb33b9U', 12),
('L', 'TNEXn3RZc0Q', 13),
('M', 'bNGIXsnO-50', 14),
('N', 'uF0WXavrbls', 15),
('O', 'ZqfjkUtm-Iw', 16),
('P', 'rRCsZEcLPIE', 17),
('Q', 'cKDMYrdBRlU', 18),
('R', 'q2whkbF6X6Y', 19),
('S', 'nS2N9qI_suk', 20),
('T', 'x4AaudNl5j4', 21),
('U', '3sEaKyyhsc8', 22),
('V', '67JmKU7F4cw', 23),
('W', 'dlcVqCTnfIQ', 24),
('X', 'hJ3msynk9Lw', 25),
('Y', 'dlcVqCTnfIQ', 26),
('Z', '2kyw0EPb0HU', 27);


-- ==========================================
-- LIÇÕES
-- ==========================================

INSERT INTO licoes VALUES
(1,1,'O Alfabeto Manual',1,0),
(2,1,'Saudações Básicas',2,0),
(3,1,'Números de 1 a 10',3,0),
(4,1,'Cumprimentos',4,0),
(5,1,'Família',5,0),
(6,2,'As Cores',1,0),
(7,2,'Tempo e Clima',2,0),
(8,2,'Alimentos',3,0),
(9,1,'Teste de Fixação',6,0),
(10,1,'Teste',2,0),
(11,1,'Hamlet',1,0),
(12,3,'dias',3,1);


-- ==========================================
-- QUESTÕES
-- ==========================================

INSERT INTO questoes VALUES
(1,'Como se diz \'Olá\' em LIBRAS?','multipla_escolha',9),
(2,'Como é a letra \'A\' em LIBRAS?','multipla_escolha',1),
(3,'Qual a expressão facial mais comum ao sinalizar \'Bom dia\'?','multipla_escolha',2),
(4,'Para expressar QUANTIDADE, como fazemos o número 3 em LIBRAS?','multipla_escolha',3),
(5,'Qual é a combinação de sinais para perguntar \'Tudo bem?\'','multipla_escolha',4),
(6,'Geralmente, o sinal de \'Mãe\' envolve tocar qual parte do corpo?','multipla_escolha',5),
(7,'O sinal da cor \'Amarelo\' é feito passando o dedo indicador em qual região?','multipla_escolha',6),
(8,'Para expressar que está \'Frio\', qual movimento corporal usamos?','multipla_escolha',7),
(9,'Como se sinaliza a palavra \'Água\' em LIBRAS?','multipla_escolha',8),
(10,'Qual é o sinal correto para a cor "Azul" em LIBRAS?','multipla_escolha',6),
(11,'Como se sinaliza a palavra "Quente" em LIBRAS?','multipla_escolha',7),
(12,'Qual é o sinal correto para "Maçã" em LIBRAS?','multipla_escolha',8),
(13,'Associe cada saudação ao seu sinal em LIBRAS.','associacao',1),
(14,'Associe cada sinal ao membro da família em LIBRAS.','associacao',5),
(15,'Relacione cada cor ao seu sinal em LIBRAS.','associacao',6),
(17,'Quantos dedos são levantados para representar o número 5 em LIBRAS?','aberta',3),
(18,'Qual palavra é representada pelo sinal de mão espalmada balançando de um lado para o outro?','aberta',4);


-- ==========================================
-- QUESTÕES DE MÚLTIPLA ESCOLHA
-- ==========================================

INSERT INTO questoes_multipla_escolha VALUES
(1,'Assinale a opção com o sinal de Olá.','texto'),
(2,'Selecione a opção correta','texto'),
(3,'','texto'),
(4,'','texto'),
(5,'','texto'),
(6,'','texto'),
(7,'','texto'),
(8,'','texto'),
(9,'','texto'),
(10,'Qual é o sinal correto para a cor "Azul" em LIBRAS?','texto'),
(11,'Como se sinaliza a palavra "Quente" em LIBRAS?','texto'),
(12,'Qual é o sinal correto para "Maçã" em LIBRAS?','texto');


INSERT INTO opcoes_questao VALUES
(1,1,'Mão fechada balançando','texto',0),
(2,1,'Mão espalmada balançando de um lado pro outro','texto',1),
(3,1,'Dedo indicador apontando pra frente','texto',0),

(4,2,'Mão fechada com polegar encostado na lateral','texto',1),
(5,2,'Mão aberta com os dedos separados','texto',0),
(6,2,'Dedos cruzados','texto',0),

(7,3,'Expressão brava ou séria','texto',0),
(8,3,'Sorriso leve e amigável','texto',1),
(9,3,'Rosto neutro sem emoção','texto',0),

(10,4,'Três dedos em pé (indicador, médio e anelar)','texto',0),
(11,4,'Polegar, indicador e médio esticados','texto',1),
(12,4,'Apontar para 3 coisas','texto',0),

(13,5,'Sinal de \'bom/bem\' seguido de \'joinha\' com expressão interrogativa','texto',1),
(14,5,'Mão aberta passando no rosto duas vezes','texto',0),
(15,5,'Acenar com as duas mãos para cima','texto',0),

(16,6,'Tocar a testa e o peito','texto',0),
(17,6,'Tocar o ombro duas vezes','texto',0),
(18,6,'Passar o polegar e indicador no rosto/queixo + sinal de mulher','texto',1),

(19,7,'Descendo pelo meio do rosto','texto',1),
(20,7,'Girando na ponta do nariz','texto',0),
(21,7,'Batendo no peito','texto',0),

(22,8,'Fazer vento com as mãos na frente do rosto','texto',0),
(23,8,'Tremer os braços recolhidos, batendo os dentes levemente','texto',1),
(24,8,'Esfregar a cabeça com as duas mãos','texto',0),

(25,9,'Bater o dedo indicador e médio (letra L) no queixo','texto',1),
(26,9,'Movimento de levar um copo imaginário até a boca','texto',0),
(27,9,'Agitar a mão aberta de um lado para o outro','texto',0),

(28,10,'Passar o dedo indicador no nariz','texto',0),
(29,10,'Passar o dedo indicador nos lábios','texto',1),
(30,10,'Bater a mão no peito duas vezes','texto',0),
(31,10,'Girar o polegar no queixo','texto',0),

(32,11,'Balançar as mãos rapidamente para os lados','texto',0),
(33,11,'Levantar as mãos abertas simulando vapor subindo','texto',1),
(34,11,'Encolher os ombros e abraçar o próprio corpo','texto',0),
(35,11,'Apontar para o sol com o dedo indicador','texto',0),

(36,12,'Imitar morder algo com a mão em C','texto',0),
(37,12,'Torcer o pulso com o dedo indicador na bochecha','texto',1),
(38,12,'Passar a mão espalmada sobre a cabeça','texto',0),
(39,12,'Juntar as pontas dos dedos e abrir','texto',0);


-- ==========================================
-- QUESTÕES ASSOCIATIVAS
-- ==========================================

INSERT INTO questoes_associativas VALUES
(13),
(14),
(15);


INSERT INTO itens_associativos VALUES
(9,'Polegar e indicador no queixo (lado feminino)','texto','A',14),
(10,'Mãe','texto','B',14),
(11,'Polegar e indicador na testa (lado masculino)','texto','A',14),
(12,'Pai','texto','B',14),
(13,'Dedo médio tocando o próprio ombro','texto','A',14),
(14,'Irmão','texto','B',14),
(15,'Mão em L perto do rosto com movimento suave','texto','A',14),
(16,'Irmã','texto','B',14),

(17,'Indicador descendo pelo meio do rosto','texto','A',15),
(18,'Amarelo','texto','B',15),
(19,'Esfregar as mãos abertas de forma circular','texto','A',15),
(20,'Verde','texto','B',15),
(21,'Dedo indicador nos lábios','texto','A',15),
(22,'Azul','texto','B',15),
(23,'Tocar a ponta do nariz com o indicador','texto','A',15),
(24,'Vermelho','texto','B',15),

(33,'Mão espalmada balançando de lado a lado','texto','A',13),
(34,'Olá','texto','B',13),
(35,'Fecho o polegar em formato de "o" e levanta o dedo mínimo','texto','A',13),
(36,'Oi','texto','B',13),
(37,'Mão espalmada encostadas, puxando para o corpo','texto','A',13),
(38,'Com licença','texto','B',13),
(39,'Mão espalmada tocando ao lado da testa','texto','A',13),
(40,'Obrigado','texto','B',13);


INSERT INTO pares_associativos VALUES
(5,9,10),
(6,11,12),
(7,13,14),
(8,15,16),
(9,17,18),
(10,19,20),
(11,21,22),
(12,23,24),
(17,33,34),
(18,35,36),
(19,37,38),
(20,39,40);


-- ==========================================
-- QUESTÕES ABERTAS
-- ==========================================

INSERT INTO questoes_abertas VALUES
(17,'5',
 'Quantos dedos são levantados para representar o número 5 em LIBRAS?',
 'texto'),

(18,'Olá',
 'Qual palavra é representada pelo sinal de mão espalmada balançando de um lado para o outro?',
 'texto');


-- ==========================================
-- PROGRESSO
-- ==========================================

INSERT INTO progresso_licoes VALUES
(1,1,1,1,'2026-09-04 18:48:56',0),
(2,1,2,1,'2026-09-04 18:52:11',0),
(4,1,11,1,'2026-08-26 15:51:18',0),
(5,1,10,1,'2026-09-04 18:52:16',0),
(6,1,3,1,'2026-08-28 16:43:16',0),
(7,1,4,1,'2026-08-28 17:01:30',0),
(8,1,5,1,'2026-08-28 17:01:41',0),
(9,1,9,1,'2026-08-28 17:01:51',0),
(10,1,6,1,'2026-08-28 17:05:11',0),
(12,14,1,1,'2026-08-31 18:35:20',0),
(13,2,1,1,'2026-09-04 19:44:59',0),
(14,2,11,1,'2026-09-04 13:02:57',0),
(15,2,2,1,'2026-09-04 13:08:10',0),
(16,2,10,1,'2026-09-03 22:03:01',0),
(17,2,3,1,'2026-09-03 23:48:45',0);
