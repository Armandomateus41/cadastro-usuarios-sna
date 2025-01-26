<form id="sna-form" method="POST" enctype="multipart/form-data">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required>

    <label for="cpf">CPF:</label>
    <input type="text" id="cpf" name="cpf" required>

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required>

    <label for="documento">Foto do Documento (JPG, PNG, PDF, máx. 3MB):</label>
    <input type="file" id="documento" name="documento" accept=".jpg, .png, .pdf" required>

    <button type="submit">Enviar</button>
</form>


<script>
    jQuery(document).ready(function($) {
        $('#sna-form').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: sna_ajax.ajax_url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert(response.data || 'Cadastro enviado com sucesso!');
                },
                error: function() {
                    alert('Erro ao enviar o cadastro.');
                }
            });
        });
    });
</script>
