<?php
/**
 * Plugin Name: ERP Shortcode Single
 * Description: Display data from custom tables using shortcode with DataTable and full CRUD buttons.
 * Version: 1.3.0
 * Author: ERP Developer
 */

if (!defined('ABSPATH')) exit;

class ERP_Shortcode_Plugin {
    public function __construct() {
        add_shortcode('erp_single_view', [$this, 'render_shortcode']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_erp_row_action', [$this, 'handle_row_action']);
        add_action('wp_ajax_nopriv_erp_row_action', [$this, 'handle_row_action']);
    }

    public function enqueue_assets() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js', ['jquery'], null, true);
        wp_enqueue_script('datatables-responsive', 'https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js', ['datatables'], null, true);
        wp_enqueue_style('datatables', 'https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css');
        wp_enqueue_style('datatables-responsive', 'https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css');
        wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
        wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', [], null, true);
    }

    public function render_shortcode($atts) {
        global $wpdb;
        $atts = shortcode_atts([
            'table' => '',
            'limit' => 50
        ], $atts);

        $table_name = $wpdb->prefix . $atts['table'];
        if (empty($atts['table'])) return '<p>No table defined.</p>';

        $rows = $wpdb->get_results("SELECT * FROM {$table_name} LIMIT " . intval($atts['limit']), ARRAY_A);
        if (empty($rows)) return '<p>No data found.</p>';

        
        $fields_param = array_map('trim', explode(',', $atts['fields']));
        $all_fields = array_keys($rows[0]);
        $safe_fields = $fields_param && !empty($atts['fields']) ? $fields_param : $all_fields;
        $safe_fields = array_map('sanitize_key', $safe_fields);
    
        ob_start(); ?>
        <div class="container my-4">
            <button id="erp-add-new" class="btn btn-success mb-3">Add New</button>
            <table id="erp-data-table" class="table table-bordered display responsive nowrap">
                <thead><tr>
                    <?php foreach ($safe_fields as $field): ?>
                        <th<?= $field === 'id' ? ' style="display:none;"' : '' ?>><?= esc_html($field) ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr data-id="<?= esc_attr($row['id']) ?>">
                            <?php foreach ($safe_fields as $field): ?>
                                <td<?= $field === 'id' ? ' style="display:none;"' : '' ?>><?= esc_html($row[$field]) ?></td>
                            <?php endforeach; ?>
                            <td>
                                <button class="btn btn-sm btn-primary erp-edit">Edit</button>
                                <button class="btn btn-sm btn-danger erp-delete">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="erp-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form id="erp-form" class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Manage Record</h5></div>
                    <div class="modal-body">
                        <?php foreach ($safe_fields as $field): if ($field === 'id') continue; ?>
                            <?php foreach ($safe_fields as $field): if ($field === "id") continue; ?>
  <div class="mb-3">
    <label class="form-label"><?= esc_html(ucfirst($field)) ?></label>
    <?php if (str_ends_with($field, "_id")): 
      $ref_table = $wpdb->prefix . str_replace("_id", "s", $field);
      $ref_rows = $wpdb->get_results("SELECT id, name FROM {$ref_table}", ARRAY_A);
    ?>
      <select name="<?= esc_attr($field) ?>" class="form-control">
        <option value="">-- Select --</option>
        <?php foreach ($ref_rows as $ref): ?>
          <option value="<?= esc_attr($ref["id"]) ?>"><?= esc_html($ref["name"]) ?></option>
        <?php endforeach; ?>
      </select>
    <?php else: ?>
      <input type="text" name="<?= esc_attr($field) ?>" class="form-control">
    <?php endif; ?>
  </div>
<?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('#erp-data-table').DataTable({ responsive: true });

            let currentId = 0;

            $('#erp-add-new').click(function() {
                $('#erp-form')[0].reset();
                currentId = 0;
                $('#erp-modal').modal('show');
            });

            $('.erp-edit').click(function() {
                const row = $(this).closest('tr');
                currentId = row.data('id');
                row.find('td').each(function(index) {
                    const input = $('#erp-form .form-control').eq(index);
                    if (input) input.val($(this).text());
                });
                $('#erp-form input[name="id"]').val(currentId);
                $('#erp-modal').modal('show');
            });

            $('.erp-delete').click(function() {
                if (!confirm("Delete this entry?")) return;
                const id = $(this).closest('tr').data('id');
                $.post(ajaxurl, {
                    action: 'erp_row_action',
                    nonce: '<?= wp_create_nonce('erp_nonce') ?>',
                    table: '<?= esc_js($atts['table']) ?>',
                    row_action: 'delete',
                    id: id
                }, function(res) {
                    if (res.success) location.reload();
                    else alert("Delete failed.");
                });
            });

            $('#erp-form').submit(function(e) {
                e.preventDefault();
                const fields = {};
                $('#erp-form .form-control').each(function() {
                    fields[$(this).attr('name')] = $(this).val();
                });
                $.post(ajaxurl, {
                    action: 'erp_row_action',
                    nonce: '<?= wp_create_nonce('erp_nonce') ?>',
                    table: '<?= esc_js($atts['table']) ?>',
                    row_action: currentId ? 'edit' : 'add',
                    id: currentId,
                    fields: fields
                }, function(res) {
                    if (res.success) location.reload();
                    else alert("Save failed.");
                });
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_row_action() {
        check_ajax_referer('erp_nonce', 'nonce');
        global $wpdb;

        $table = sanitize_text_field($_POST['table'] ?? '');
        $action = sanitize_text_field($_POST['row_action'] ?? '');
        $fields = $_POST['fields'] ?? [];
        $id = intval($_POST['id'] ?? 0);

        $table_name = $wpdb->prefix . $table;

        if ($action === 'delete') {
            $wpdb->delete($table_name, ['id' => $id]);
        } elseif ($action === 'edit') {
            $wpdb->update($table_name, $fields, ['id' => $id]);
        } elseif ($action === 'add') {
            $wpdb->insert($table_name, $fields);
        }

        wp_send_json_success(['message' => ucfirst($action) . ' successful']);
    }
}

new ERP_Shortcode_Plugin();
