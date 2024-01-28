<footer class="footer footer-default">
    <div class="container">
        <nav class="float-left">
                <?php social_links(); ?>
        </nav>
        <div class="copyright float-right">
            &copy;
            <script>
                document.write(new Date().getFullYear())
            </script>
            <a class="text-secondary" href="<?php echo $config["url"]; ?>"><?php echo $config["title"]; ?></a>
        </div>
    </div>
</footer>
<?php
option("tracking_code", true);
option("gdpr_notice", true);
?>
<script src="<?php echo $config["url"]; ?>/template/material/js/compressed.js" type="text/javascript"></script>
<script src="<?php echo $config["url"]; ?>/template/material/js/main.js" type="text/javascript"></script>
</body>
</html>