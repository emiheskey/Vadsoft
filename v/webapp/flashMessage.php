<?php
	if (isset($_SESSION['flash'])) {
        $flash = json_decode($_SESSION['flash']);
        echo '<div class="alert alert-'.$flash->status.'">'.$flash->message.'</div>';
		unset($_SESSION['flash']);
	}
?>