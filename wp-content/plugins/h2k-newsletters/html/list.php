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
                echo '<td class="actions">';
                echo '<a href="' . admin_url('admin.php?page=h2k-newsletters-settings&id=' . $result['id']) . '" class="button">Editar</a>';
                echo '<a href="' . admin_url('admin.php?page=h2k-newsletters-settings&delete=' . $result['id']) . '" class="button action-delete">Excluir</a>';
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

<script>
    jQuery(document).on('click', '.action-delete', function(e) {
        e.preventDefault();
        let href = jQuery(this).attr('href');

        // Substitua o alerta por um alerta de confirmação
        let confirmDelete = window.confirm('Tem certeza que deseja excluir?');

        if (confirmDelete) {
            location.href = href;
        }
    });
</script>

<style>
    .wp-list-table .actions {
        display: flex;
        gap: 8px;
    }

    .action-delete {
        background-color: red !important;
        color: #FFF !important;
        border-color: red !important;
        transition: all ease .5s;
    }

    .action-delete:hover {
        background-color: #a70606 !important;
        border-color: #a70606 !important;
    }
</style>