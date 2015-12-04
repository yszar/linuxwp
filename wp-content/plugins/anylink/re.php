<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
    setInterval( function(){top.location = "<?php echo $gotoLink ?>";} , 3000)
</script>
</head>
<body>
正在为您跳转到(Now we're relocating you to)：
<?php echo $gotoLink; ?>
</body>
</html>