<div class="wrap">
    <h1>Cadastros de Usuários</h1>
    <table class="widefat fixed" cellspacing="0">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>E-mail</th>
                <th>Documento</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $cadastros = SNA_Cadastro::get_all_cadastros();
            if ( ! empty( $cadastros ) ) {
                foreach ( $cadastros as $cadastro ) {
                    echo '<tr>';
                    echo '<td>' . esc_html( $cadastro['nome'] ) . '</td>';
                    echo '<td>' . esc_html( $cadastro['cpf'] ) . '</td>';
                    echo '<td>' . esc_html( $cadastro['email'] ) . '</td>';
                    echo '<td><a href="' . esc_url( $cadastro['documento'] ) . '" target="_blank">Visualizar</a></td>';
                    echo '<td>' . esc_html( $cadastro['status'] ) . '</td>';
                    echo '<td>
                        <button class="button approve" data-id="' . esc_attr( $cadastro['id'] ) . '">Aprovar</button>
                        <button class="button reject" data-id="' . esc_attr( $cadastro['id'] ) . '">Reprovar</button>
                    </td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6">Nenhum cadastro encontrado.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    jQuery(document).ready(function($) {
        $('.approve, .reject').on('click', function() {
            const id = $(this).data('id');
            const status = $(this).hasClass('approve') ? 'Aprovado' : 'Reprovado';

            $.post(sna_ajax.ajax_url, {
                action: 'sna_update_status',
                id: id,
                status: status,
            }, function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.data || 'Erro ao atualizar status.');
                }
            });
        });
    });
</script>
