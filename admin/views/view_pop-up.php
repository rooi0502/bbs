<style>
#overlay {
	width:100%;
	height:100%;
	top:0;
	left:0;
	background:rgba(0, 0, 0, 0.3);
	position:fixed;
	display:none;
	z-index:1;
}

#modal_window {
	width:50%;
	max-height:70%;
	overflow:auto;
	display:none;
	position:fixed;
	z-index:2;
	background: #d8bfd8;
}

#image {
	display:none;
}

#checkbox {
	display:none;
	transform:scale(2);
}

.button_link {
	color:#00f;
	text-decoration:underline;
}

.button_link:hover {
	cursor:pointer;
	color:#f00;
}
</style>

<div id="overlay"></div>
<div id="modal_window">
	<form method="post" name="form" id="form" enctype="multipart/form-data">
		<h2>編集</h2>
		<div id="attr" hidden></div>
		<p>ID:</p>
		<div id="id" name="id"></div>
		<div><p>名前:</p>
			<input type="text" name="name" value="" id="name" />
		</div>
		<div><p>本文:</p>
			<textarea id="text" name="text" rows="10" cols="30"></textarea>
		</div>
		<div id="time"></div>
		<div><p>文字色:</p>
			<input type="text" name="color" value="" id="color" />
		</div>
		<div id="user_id"></div>
		<div id="image"><p>画像</p></div>
		<div><img src="" id="fname" name=""></div>
		<div id="checkbox_text">画像の削除</div>
		<input type="checkbox" id="checkbox" name="" value="delete">
		<div><input type="file" name="upfile" id="file" /></div>
		<div><input type="button" name="btn" id="store" value="保存"></div>
	</form>
	<a id="modal_close" class="button_link">閉じる</a>
</div>