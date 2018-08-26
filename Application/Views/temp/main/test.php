<h1> Форма 1</h1>
<form action="/form1" method="post">
	<p> Логин</p>
	<p><input type="text" name="login"></p>
	<p> Пароль</p>
	<p><input type="password" name="password"></p>
	<p>
		<button type="submit" value="Отправить">Отправить</button>
	</p>
</form>

<h1> Форма 2</h1>
<form action="form2" method="post" enctype="multipart/form-data">
	<p> Файл</p>
	<p><input type="file" name="file"></p>
	<p>
		<button type="submit" value="Отправить">Отправить</button>
	</p>
</form>