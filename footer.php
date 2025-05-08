</div> <!-- .row -->
</div> <!-- .container-fluid -->

<footer class="bg-light text-center py-3 mt-4 border-top">
    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
</footer>

<?php wp_footer(); ?>
<script>
document.getElementById('sidebarToggle')?.addEventListener('click', () => {
    document.querySelector('aside')?.classList.toggle('show');
});
</script>
</body>
</html>