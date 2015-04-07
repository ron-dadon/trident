<?php


class Main_Index_View extends Trident_Abstract_View
{
    public function render()
    {
        $this->include_shared_view('header');
    ?>
    <h1>Main Index</h1>
    <p>
        <strong>Users list:</strong>
        <ul>
        <?php foreach ($this->get('users') as $user): ?>
            <li><?php echo $user->id . ', ' . $user->name . ', ' . $user->e_mail ?></li>
        <?php endforeach; ?>
        </ul>
    </p>
    <?php
        $this->include_shared_view('footer');
    }
}