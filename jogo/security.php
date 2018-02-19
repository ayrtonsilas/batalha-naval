<?php
session_start();
if(!isset($_SESSION['user'])){
	echo"<script>location.href='index.php';</script>";
}
?>