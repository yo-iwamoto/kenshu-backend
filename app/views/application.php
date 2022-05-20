<?php
use App\views\partials;

?>

<!DOCTYPE html>

<?php partials\Head::render() ?>

<body>
    <?php partials\Header::render($is_authenticated) ?>
    <main class="font-mono">
        <?php echo $content; ?>
    </main>
</body>

</html>