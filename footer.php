<?php if(!is_front_page()) { ?>
<footer class="container">
    <hr>
    <div class="row">
        <div class="col">
            <p class="text-center">&copy; <?php echo date('Y'); ?> <a href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a></p>
        </div>
    </div>
</footer>
<?php } ?>
<?php wp_footer(); ?>
</body>
</html>