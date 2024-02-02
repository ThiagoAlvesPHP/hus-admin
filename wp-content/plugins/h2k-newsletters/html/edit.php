<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
    <div class="updated">
        <p>Dados atualizados com sucesso!</p>
    </div>
<?php endif; ?>

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