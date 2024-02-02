<?php

/**
 * Newsletters H2K
 * 
 * Plugin Name:     Newsletters REST API
 * Version:         1.0.0
 * Description:     Newsletters H2K
 * Author:          H2K
 * Author URI:      https://h2k.com.br/
 */

register_activation_hook(__FILE__, 'h2k_newsletters_activation');

function h2k_newsletters_activation()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'h2k_newsletters';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          name varchar(255) NOT NULL,
          email varchar(255) NOT NULL UNIQUE,
          accepted boolean NOT NULL,
          created_at datetime DEFAULT CURRENT_TIMESTAMP,
          updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY  (id)
      ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('rest_api_init', 'h2k_newsletters_register_rest_route');

function h2k_newsletters_register_rest_route()
{
    register_rest_route('h2k/v1', '/newsletter', array(
        'methods'  => 'POST',
        'callback' => 'h2k_newsletters_handle_api_request',
        'args'     => array(
            'name'     => array(
                'required'    => true,
                'validate_callback' => function ($param, $request, $key) {
                    return is_string($param);
                }
            ),
            'email'    => array(
                'required'    => true,
                'validate_callback' => function ($param, $request, $key) {
                    return is_email($param);
                }
            ),
            'accepted' => array(
                'required'    => true,
                'validate_callback'  => function ($param, $request, $key) {
                    return $param === true;
                }
            ),
        ),
    ));
}

function h2k_newsletters_handle_api_request($data)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'h2k_newsletters';

    $name = sanitize_text_field($data['name']);
    $email = sanitize_email($data['email']);
    $accepted = (bool) $data['accepted'];

    // Verificar se o e-mail já existe
    $existing_email = $wpdb->get_var($wpdb->prepare("SELECT email FROM $table_name WHERE email = %s", $email));

    if ($existing_email) {
        return new WP_Error('email_exists', 'O e-mail já está registrado.', array('status' => 409));
    }

    // Se o e-mail não existir, realizar a inserção
    $wpdb->insert(
        $table_name,
        array(
            'name'      => $name,
            'email'     => $email,
            'accepted'  => $accepted,
        ),
        array(
            '%s',
            '%s',
            '%d',
        )
    );

    return array('success' => true);
}

// Adiciona ação ao hook 'admin_menu'
add_action('admin_menu', 'h2k_newsletters_add_admin_menu');

// Função para adicionar a nova aba no menu de administração
function h2k_newsletters_add_admin_menu()
{
    // Adiciona uma nova aba no menu 'Configurações'
    add_menu_page(
        'Configurações de Newsletter', // Título da aba
        'Newsletter H2K',              // Título no menu
        'manage_options',               // Capacidade necessária para acessar
        'h2k-newsletters-settings',     // Identificador único da página
        'h2k_newsletters_settings_page', // Função que renderiza a página
        'dashicons-email'               // Ícone (pode ser substituído por um ícone do Dashicons)
    );
}

// Função para renderizar a página de configurações
function h2k_newsletters_settings_page()
{
    if (isset($_GET['id'])) {
        h2k_newsletters_edit_page();
    } else {
        h2k_newsletters_list_page();
    }
}

/**
 * list
 */
function h2k_newsletters_list_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'h2k_newsletters';

    // Obtém todos os registros da tabela
    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    ?>
        <div class="wrap">
            <h2>Configurações de Newsletter H2K</h2>
            <!-- Conteúdo da sua página de configurações aqui -->

            <h3>Lista de Registros:</h3>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Ações</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Aceito</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Exibe os dados na tabela
                    foreach ($results as $result) {
                        echo '<tr>';
                        echo '<td>';
                        echo '<a href="' . admin_url('admin.php?page=h2k-newsletters-settings&id=' . $result['id']) . '" class="button">Editar</a>';
                        echo '<a href="#" class="button button-secondary">Excluir</a>';
                        echo '</td>';
                        echo '<td>' . $result['name'] . '</td>';
                        echo '<td>' . $result['email'] . '</td>';
                        echo '<td>' . ($result['accepted'] ? 'Sim' : 'Não') . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
}


function h2k_newsletters_edit_page()
{
    global $wpdb;

    // Verifica se o ID do registro está presente
    if (isset($_GET['id'])) {
        $id = absint($_GET['id']);

        // Obtém os dados do registro pelo ID
        $table_name = $wpdb->prefix . 'h2k_newsletters';
        $record = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);

        // Se o registro existe, renderiza o formulário de edição
        if ($record) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Processa o formulário quando enviado
                $name = sanitize_text_field($_POST['name']);
                $email = sanitize_email($_POST['email']);
                $accepted = isset($_POST['accepted']) ? 1 : 0;

                // Atualiza os dados no banco de dados
                $wpdb->update(
                    $table_name,
                    array(
                        'name' => $name,
                        'email' => $email,
                        'accepted' => $accepted,
                    ),
                    array('id' => $id),
                    array('%s', '%s', '%d'),
                    array('%d')
                );

                echo '<div class="updated"><p>Dados atualizados com sucesso!</p></div>';
                $record = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
            }

            ?>
            <div class="wrap">
                <h2>Editar Registro</h2>
                <form method="post">
                    <table class="form-table">
                        <tr>
                            <th><label for="name">Nome</label></th>
                            <td><input type="text" name="name" id="name" value="<?php echo esc_attr($record['name']); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="email">E-mail</label></th>
                            <td><input type="email" name="email" id="email" value="<?php echo esc_attr($record['email']); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="accepted">Aceito</label></th>
                            <td><input type="checkbox" name="accepted" id="accepted" value="1" <?php checked($record['accepted'], 1); ?>></td>
                        </tr>
                    </table>
                    <?php submit_button('Atualizar Dados'); ?>
                </form>
            </div>
            <?php
        } else {
            // Se o registro não existe, redireciona para a página principal ou faz algo apropriado
            wp_redirect(admin_url('admin.php?page=h2k-newsletters-settings'));
            exit;
        }
    } else {
        // Se o ID do registro não está presente, redireciona para a página principal ou faz algo apropriado
        wp_redirect(admin_url('admin.php?page=h2k-newsletters-settings'));
        exit;
    }
}
