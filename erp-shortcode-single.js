
jQuery(document).ready(function($) {
    const modal = $('#erp-modal');
    const form = $('#erp-form');
    let currentId = 0;

    const table = $('#erp-data-table').DataTable({ responsive: true });

    $('#erp-add-new').click(function () {
        currentId = 0;
        form[0].reset();
        form.find('input[name="id"]').val('');
        modal.modal('show');
    });

    $('#erp-data-table tbody').on('click', '[data-action="edit"]', function () {
        const tr = $(this).closest('tr');
        const row = table.row(tr);
        const rowData = row.data();
        const headers = $('#erp-data-table thead th').map(function () {
            return $(this).text().trim().toLowerCase();
        }).get();

        if (!rowData) return;

        currentId = tr.data('id') || 0;
        form.find('input[name="id"]').val(currentId);

        headers.forEach((header, i) => {
            if (header === "actions" || header === "id") return;
            const input = form.find('[name="' + header + '"]');
            if (input.length && rowData[i]) {
                input.val(rowData[i]);
            }
        });

        modal.modal('show');
    });

    form.submit(function (e) {
        e.preventDefault();
        const fields = {};
        form.find('.form-control').each(function () {
            const key = $(this).attr('name');
            if (key !== 'id') {
                fields[key] = $(this).val();
            }
        });

        $.post(erp_ajax.ajax_url, {
            action: 'erp_row_action',
            nonce: erp_ajax.nonce,
            table: $('.erp-data-view').data('table'),
            row_action: currentId ? 'edit' : 'add',
            id: currentId,
            fields: fields
        }, function (res) {
            if (res.success) {
                alert(res.data.message);
                location.reload();
            } else {
                alert("Failed to save.");
            }
        });
    });

    $('#erp-data-table tbody').on('click', '[data-action="delete"]', function () {
        if (!confirm("Delete this entry?")) return;
        const id = $(this).closest('tr').data('id');
        $.post(erp_ajax.ajax_url, {
            action: 'erp_row_action',
            nonce: erp_ajax.nonce,
            table: $('.erp-data-view').data('table'),
            row_action: 'delete',
            id: id
        }, function (res) {
            if (res.success) {
                alert(res.data.message);
                location.reload();
            } else {
                alert("Failed to delete.");
            }
        });
    });
});
