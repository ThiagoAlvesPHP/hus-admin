<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do banco de dados
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Configurações do banco de dados - Você pode pegar estas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define( 'DB_NAME', 'wp_hus' );

/** Usuário do banco de dados MySQL */
define( 'DB_USER', 'root' );

/** Senha do banco de dados MySQL */
define( 'DB_PASSWORD', 'root' );

/** Nome do host do MySQL */
define( 'DB_HOST', 'localhost' );

/** Charset do banco de dados a ser usado na criação das tabelas. */
define( 'DB_CHARSET', 'utf8mb4' );

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define( 'DB_COLLATE', '' );

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'DT u>l,Kr%I}tI(/5skHwg;&t@p^@}6~ySD08B|N2Xe?;pyVQo![0U?N9}9}lx8?' );
define( 'SECURE_AUTH_KEY',  'otAtn(D1kivVmM-M#hEA4AKbITLm<5}BZjy;M`8KEii5#R&wrLs=`&~<HcZeWSsK' );
define( 'LOGGED_IN_KEY',    'fiddcUawM]A[|Y#{?@&xK;-a5V[/pIuFErns<|v1IiP,iu%,%(18}O3saddx#^>U' );
define( 'NONCE_KEY',        'YFAY_(awm-<0_S--IHqN&^9lR::fBCJ]xNs-ojf^jIf@pkfE;wr-@OXp^n/ThT5`' );
define( 'AUTH_SALT',        'Iwxr?>y?.j9i%A`#`=hvOuDl,<KmM`%! pE]tx{P*t1r%GS1Qjv7lvN#K[Zpf2aX' );
define( 'SECURE_AUTH_SALT', 'LOR2ZswZMTSnu6A/$0$-rQ3,*L+ %]v_BZO&(L!^~d}JRi^ERo:ItB1&pkPDpxBv' );
define( 'LOGGED_IN_SALT',   ' kXl,^IsR01S>,= EU 4x9;}tDLgaH@n#1Czz5e(~,m5TS#I/=rqo-*li zWV){h' );
define( 'NONCE_SALT',       '>/Tz-^0$;QlPCsnD:_ii5Gy#xjac>sg,Ux{>1vZA5jYWq[l-SV ZHW_N7v:!?<<=' );

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix = 'wp_';

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Adicione valores personalizados entre esta linha até "Isto é tudo". */



/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Configura as variáveis e arquivos do WordPress. */
require_once ABSPATH . 'wp-settings.php';
