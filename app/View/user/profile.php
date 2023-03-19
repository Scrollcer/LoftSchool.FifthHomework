<?php
/** @var \App\Model\User $user */
$user = $this->user;
?>

<?= $this->title; ?>

<?php if ($user): ?>
    Пользователь ID: <?= $user->getId(); ?> с именем <?= $user->getName(); ?>
<?php else: ?>
    Пользователь не найден
<?php endif; ?>
