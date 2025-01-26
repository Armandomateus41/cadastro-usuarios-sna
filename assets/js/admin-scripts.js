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
                if (response.success) {
                    alert(response.data);
                    location.reload();
                } else {
                    alert(response.data || 'Erro ao enviar o cadastro.');
                }
            },
            error: function() {
                alert('Erro inesperado. Tente novamente.');
            }
        });
    });

    // Validação do CPF
    $('#cpf').on('blur', function() {
        const cpf = $(this).val().replace(/\D/g, '');

        if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) {
            alert('CPF inválido.');
            $(this).val('');
        }
    });

    // Validação do Documento
    $('#documento').on('change', function() {
        const file = this.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];

        if (!allowedTypes.includes(file.type) || file.size > 3 * 1024 * 1024) {
            alert('Arquivo inválido. Envie JPG, PNG ou PDF com no máximo 3MB.');
            $(this).val('');
        }
    });
});
