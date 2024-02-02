<div class="wrap">
    <h2>Mensagem</h2>
    <!-- Conteúdo da sua página de configurações aqui -->

    <h3>Formulário de Mensagem:</h3>
    <form class="form-send-newsletters" action="" method="POST">
        <input type="text" name="subject" class="regular-text" placeholder="Assunto" required>
        <div class="wp-editor-container">
            <?php
            $content = ''; // Conteúdo inicial do editor
            $editor_id = 'my_custom_editor'; // Um ID único para o editor

            // Parâmetros da função wp_editor
            $settings = array(
                'textarea_name' => 'message', // Nome do campo de texto para processar os dados
                'editor_class'  => 'custom-editor-class', // Classe CSS personalizada para o editor
                'media_buttons' => true, // Mostrar botões de mídia (imagens, vídeos, etc.)
                'tinymce'       => true, // Usar o editor visual TinyMCE
                'quicktags'     => true, // Usar as caixas de edição rápida de tags
            );

            wp_editor($content, $editor_id, $settings);
            ?>
        </div>
        <button class="button">Disparar para E-mails</button>
    </form>
</div>


<style>
    .form-send-newsletters {
        display: grid;
        gap: 8px;
    }

    .regular-text {
        width: 100%;
    }
</style>