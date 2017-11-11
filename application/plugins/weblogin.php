<?php

if($telegram->text_has(["participar", "página"]) && $telegram->text_has(["sorteo"]) && $telegram->words() <= 9){
	if($telegram->is_chat_group()){
		$str = "¡Consigue incienso y cebos para Telegram!\nhttp://profoak.me/";
	}else{
		$key = md5($telegram->user->id .":" .time());
		$query = $this->db
			->set('uid', $telegram->user->id)
			->set('webkey', $key)
		->insert('weblogin');

		$str = "Este es un link exclusivo para ti, ¡no se lo pases a nadie!\nhttp://profoak.me/login/$key";
	}

	$telegram->send
		->notification(FALSE)
		->text($str)
	->send();

	return -1;
}

if($telegram->is_chat_group()){ return; }

if($telegram->text_command("start") && $telegram->text_contains("weblogin") && $telegram->words() <= 3){
	$webkey = $telegram->last_word(TRUE);
	$webkey = str_replace("weblogin", "", $webkey);
	$data = ['uid' => $telegram->user->id, 'webkey' => $webkey];

	$query = $this->db->insert_string('weblogin', $data);
	$query = str_replace('INSERT INTO','INSERT IGNORE INTO', $query);
	$this->db->query($query);

	$telegram->send
		->text("¡Login hecho! Ya puedes volver.")
		->inline_keyboard()
			->row_button("Abrir web", "http://profoak.me")
		->show()
	->send();
	return -1;
}


?>
