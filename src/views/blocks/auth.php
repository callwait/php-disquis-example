<?php
use helpers\Text;
$text = new Text;
?>

<form action="/auth/in/" method="post">
    <legend>Войдите через социальную сеть</legend>
    <?php foreach ($this->providers['provider'] as $name => $parameters) : ?>
        <?php
        $enabled = true;
        if (isset($parameters['enabled'])) {
            $enabled = (bool) $parameters['enabled'];
        }
        $name = strtolower($name);
        $icon = $text->loadSvg($name);
        if($icon) {
        ?>
        <button name="provider" type="submit" class="wp-social big wp-<?php echo $name; ?>" value="<?php echo $name; ?>"<?php echo (!$enabled) ? ' disabled="disabled"' : ''; ?> title="<?php echo $name; ?>">
            <?=$icon;?>
        </button>
    <?php } endforeach; ?>
</form>

<style>
    button {
        background-color: transparent;
        border: none;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
    }
</style>