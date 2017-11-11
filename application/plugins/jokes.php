<?php

if($telegram->is_chat_group()){
	if($pokemon->settings($telegram->chat->id, 'shutup')){ return; }
	$jokes = $pokemon->settings($telegram->chat->id, 'jokes');
	if($jokes != NULL && $jokes == FALSE){ return; }
}

$joke = NULL;

if(
    ( $telegram->text_has(["necesitas", "necesitáis"], ["novio", "un novio", "novia", "una novia", "pareja", "una pareja", "follar"]) ) or
    ( $telegram->text_has("Tengo", TRUE) && $telegram->words() == 2 && !is_numeric($telegram->last_word()) )
){
    $word = ($telegram->text_has("Tengo", TRUE) ? ucwords(strtolower($telegram->last_word())) : "Novia");
    if(strlen($word) > 8){ return; }
    $joke = "¿$word? Qué es eso, ¿se come?";
}elseif(
    $telegram->text_contains(["oak", "profe"]) &&
    $telegram->text_has(["cuántos", "cuándo", "qué"]) &&
    $telegram->text_contains(["años", "edad", "cumple"])
){
    $release = strtotime("2016-07-16 14:27");
    $birthdate = strtotime("now") - $release;
    $days = floor($birthdate / (60*60*24)) - 5; // -5 dias, 360
	if($days % 360 == 0){ // TODO Cuenta 360 dias, no 365
		$joke = "¡Es mi cumpleaños! Puedes felicitar a @duhow también.";
	}else{
		$joke = "Cumplo " .floor($days/30) ." meses y " .($days % 30) ." días.";
	}
    $joke .= $this->telegram->emoji(" :)");
}elseif($telegram->text_has("quién es Ash") && $telegram->words() <= 7){
	$this->analytics->event('Telegram', 'Jokes', 'Ash');
	$joke = "Ah! Ese es un *cheater*, es nivel 100...\nLo que no sé de dónde saca tanto dinero para viajar tanto...";
}elseif($telegram->text_has("quién es Brock") && $telegram->words() <= 7){
	$this->analytics->event('Telegram', 'Jokes', 'Brock');
	$joke = "Eh! Ese es el mejor criador de Pokémon, líder de gimnasio, y amigo de Ash... Pero en cuanto ve una falda se pierde... Por cierto, llevamos temporadas sin verle :O";
}elseif($telegram->text_has("quién es Misty") && $telegram->words() <= 7){
	$this->analytics->event('Telegram', 'Jokes', 'Misty');
	$joke = "Es la líder del gimnasio Celeste... Pero lo abandonó porque se enamoró loca y secretamente de Ash, con la excusa de que le pagará una bici.";
}elseif($telegram->text_has("quién es Rojo") && $telegram->words() <= 7){
	$this->analytics->event('Telegram', 'Jokes', 'Rojo');
	$joke = "Ese... ese es un cabrón... Le quitó el titulo a mi nieto nada más obtenerlo :(";
}elseif($telegram->text_has("quién es Gary") && $telegram->words() <= 7){
	$this->analytics->event('Telegram', 'Jokes', 'Gary');
	$joke = "Anda... Pero si es mi nieto... ¿es chico o chica?... y... ¿cómo dices que se llama?";
}elseif($telegram->text_has("quién es Giovanni") && $telegram->words() <= 7){
	$this->analytics->event('Telegram', 'Jokes', 'Giovanni');
	$joke = "Creador y fundador del Team Rocket, último líder de Kanto, y quien envía al Team Rocket para quitaros objetos del inventario. También se dice que es el director de Hacienda.";
}elseif($telegram->text_has("quién es Lance") && $telegram->words() <= 7){
	$this->analytics->event('Telegram', 'Jokes', 'Lance');
	$joke = "Domadragones campeón de la liga, hasta que mi nieto Gary lo derrotó... Desde entonces ha estado entrenando en secreto.";
}elseif($telegram->text_has("quién es N") && $telegram->words() <= 7){
	$this->analytics->event('Telegram', 'Jokes', 'N');
	$joke = "Este es un entrenador amigo de los Pokémon, pero es un fetichista raro... Escuché de mi amiga, la profesora Encina, que N se quiere casar con su Gardevoir...";
}elseif($telegram->text_has("quien es", ["tu amante", "el amor de tu vida", "tu novio", "tu novia", "ese tio bueno"]) and $telegram->words() <= 9){
	// Donator command
	$frases = [
		"Oh, cada vez que me acuerdo de su dulce cu...",
		"Sólo sé quién es cuando lo noto por detr... OH SI SEMPAAAII!!! <3",
		"¡Uy! Solo te diré que es un tío muy atlético. <3",
	];
	$n = mt_rand(0, count($frases) - 1);
	$joke = $frases[$n];
}elseif($telegram->text_has(["profesor", "profe", "oak"]) and $telegram->text_has(["chico", "hombre"]) and $telegram->text_has(["chica", "mujer"]) and $telegram->text_contains("?") and $telegram->words() <= 8){
	$this->analytics->event('Telegram', 'Jokes', 'Chico o chica');
	$joke = "Pues no estoy muy seguro. A veces me siento Evax. :>";
}elseif($telegram->text_has(["pokémon", "niantic"]) and $telegram->text_has(["mal", "mierda", "puto", "puta", "cagada", "cagadas", "cagando", "fatal"])){
	$joke = "Oye, pues si tan mal va Pokémon GO, ¿porqué no echas el currículum y arreglas sus fallos?\n"
			."Te dejo el link: https://www.nianticlabs.com/jobs/";
}elseif(
	$this->telegram->text_has(["glados", "gla2", "feliz cumple", "felicidades", "pastel", "cake", "lie"]) or
	$this->telegram->sticker()
){
	$can = TRUE;
	if($this->telegram->sticker()){
		$stickers = [
			"CAADBAAD6AADXOgKAAEYtNpmQ50VuAI",
			"CAADAQADSQIAAqYkxgABFG0TeSWuBzcC",
			"CAADAQADuQIAAqYkxgABLnSkBun5HrQC"
		];
		if(!in_array($this->telegram->sticker(), $stickers)){ $can = FALSE; }
	}
	if($can and mt_rand(1, 3) == 3){
		$icon = ["\ud83d\ude08", "\ud83d\ude06", "\ud83e\udd10"];
		$n = mt_rand(0, count($icon) - 1);
		$joke = "The cake is a lie. " .$this->telegram->emoji($icon[$n]);
	}
}elseif($telegram->text_has("Gracias", ["profesor", "Oak", "profe"]) && !$telegram->text_has("pero", "no")){
	// "el puto amo", "que maquina eres"
	$this->analytics->event('Telegram', 'Jokes', 'Thank you');
	$frases = ["De nada, entrenador! :D", "Nada, para eso estamos! ^^", "Gracias a ti :3"];
	$n = mt_rand(0, count($frases) - 1);

	$joke = $frases[$n];
}elseif($telegram->text_has(["buenos", "buenas", "bon"], ["días", "día", "tarde", "tarda", "tardes", "noches", "nit"])){
	/* if(
		($telegram->is_chat_group() and $pokemon->settings($telegram->chat->id, 'say_hello') == TRUE) and
		($pokemon->settings($telegram->user->id, 'say_hello') != FALSE or $pokemon->settings($telegram->user->id, 'say_hello') == NULL)
	){*/
	if($pokemon->command_limit("hello", $telegram->chat->id, $telegram->message, 7)){ return -1; }
	$joke = "Buenas a ti también, entrenador! :D";
	if($telegram->text_has(['noches', 'nit'])){
		$joke = "Buenas noches fiera, descansa bien! :)";
	}
}elseif($telegram->text_hashtag("novatos")){
    $this->analytics->event('Telegram', 'Jokes', 'Question');
    $preguntas = [
        "¿Nombre?", "¿Edad?", "¿Lugar de residencia?",
        "¿Tendencia sexual?", "Foto de tu cara", "¿Tragas o escupes?"
    ];
    $texto = "";
    for($i = 0; $i < count($preguntas); $i++){
        $texto .= ($i+1) .".- $preguntas[$i]\n";
    }
    $telegram->send
        ->notification(FALSE)
        ->text($texto)
    ->send();
    return;
}elseif($telegram->text_command("drama")){
    $drama = [
        'BQADAwADXgADVC-4BxFsybPmJZnnAg', // Judges you in Spanish
        'BQADAwADWAADVC-4B-5sJxB9W3QUAg', // Cries in Spanish
        'BQADAwADaAADVC-4B-Sq7oqcxWkyAg', // Screams in Spanish
        'BQADAwADxwADVC-4BxbymhHL_2iYAg', // Gets nervous in Spanish
    ];
    $n = mt_rand(0, count($drama) - 1);
    $this->analytics->event('Telegram', 'Jokes', 'Drama');
    $telegram->send->notification(FALSE)->file('sticker', $drama[$n]);
    return;
}elseif($telegram->text_has(["saluda", "saludo"]) && $telegram->text_has(["profe", "profesor", "oak"])){
    /* if(!$this->is_shutup()){
        $joke = "Un saludo para todos mis fans! :D";
    } */
}elseif($telegram->text_has(["a que sí"], ["profe", "oak", "profesor"])){
    $this->analytics->event('Telegram', 'Jokes', 'Reply yes or no');
    $resp = ["¡Por supuesto que sí!",
        "Mmm... Te equivocas.",
        "No creo que tu madre esté de acuerdo con eso... ;)",
        "Ahora mismo no te puedo decir...",
        "¿¡Pero tú por quién me tomas!?",
        "Pues ahora me has dejado con la duda...",
    ];
    $n = mt_rand(0, count($resp) - 1);
    // if($this->is_shutup()){ return; }
    $joke = $resp[$n];
}elseif($telegram->text_has("Gracias", ["profesor", "Oak", "profe"]) && !$telegram->text_has("pero", "no")){
    // "el puto amo", "que maquina eres"
    $this->analytics->event('Telegram', 'Jokes', 'Thank you');
    $frases = ["De nada, entrenador! :D", "Nada, para eso estamos! ^^", "Gracias a ti :3"];
    $n = mt_rand(0, count($frases) - 1);

    $joke = $frases[$n];
}elseif($telegram->text_has(["Ty bro", "ty prof"])){
    if($pokemon->settings($telegram->chat->id, 'jokes') == FALSE){ return; }
    $joke = "Yeah ma nigga 8-)";
}elseif($telegram->text_has("Profesor Oak", TRUE)){
    // if(!$this->is_shutup()){ $joke = "Dime!"; }
}elseif($telegram->text_has(["alguien", "alguno"]) && $telegram->text_has(["decir", "dice", "sabe"])){
    if(mt_rand(1, 7) == 7){ $joke = "pa k kieres saber eso jaja salu2"; }
}elseif($telegram->text_has(["programado", "funcionas"]) && $telegram->text_has(["profe", "profesor", "oak", "bot"])){
    $joke = "Pues yo funciono con *PHP* (_CodeIgniter_) :)";
    // pregunta sobre el creador de Oak
}elseif(
    !$telegram->text_has(["qué", "cómo"]) &&
    $telegram->text_has(["quién", "oak", "profe"]) &&
    $telegram->text_has(["es", "te", "tu", "hizo a", "le"]) &&
    $telegram->text_has(["programado", "hecho", "hizo", "creado", "creador"]) &&
    $telegram->words() <= 8
){
    $telegram->send->notification(FALSE)->text("Pues mi creador es @duhow :)")->send();
    return;
}elseif($telegram->text_has("qué hora", ["es", "son"]) && !$telegram->text_has("a qué hora") && $telegram->text_contains("?") && $telegram->words() <= 5){
    $this->analytics->event('Telegram', 'Jokes', 'Time');
    $joke = "Son las " .date("H:i") .", una hora menos en Canarias. :)";
}elseif($telegram->text_has(["profe", "profesor", "oak"]) && $telegram->text_has("te", ["quiero", "amo", "adoro"])){
    // if(!$this->is_shutup()){ $joke = "¡Yo también te quiero! <3"; }
}elseif($telegram->text_contains(["te la com", "te lo com", "un hijo", "me ha dolido"]) && $telegram->text_has(["oak", "profe", "bot"])){
    if($telegram->text_has("no")){
        $joke = "¿Pues entonces para que me dices nada? Gilipollas.";
    }else{
        // if($this->is_shutup_jokes()){ return; }

        $joke = "Tu sabes lo que es el fiambre? Pues tranquilo, que no vas a pasar hambre... ;)";
        $telegram->send
            ->notification(FALSE)
            ->file('sticker', 'BQADBAADGgAD9VikAAEvUZ8dGx1_fgI');
    }
}elseif($telegram->text_command("banana") && $telegram->has_reply){
    $this->analytics->event('Telegram', 'Jokes', 'Banana');
    $text = "Oye " .$telegram->reply_user->first_name .", " .$telegram->user->first_name ." quiere darte su banana... " .$telegram->emoji("=P");
    if($telegram->reply_user->id == $this->config->item('telegram_bot_id')){
        $text = "Oh, asi que quieres darme tu banana, " .$telegram->user->first_name ."? " .$telegram->emoji("=P");
        $telegram->send
            ->chat($this->config->item('creator'))
            ->text($telegram->user->first_name ." @" .$telegram->user->username ." / @" .$pokeuser->username ." quiere darte su banana.")
        ->send();
    }
    $telegram->send
        ->notification(FALSE)
        ->reply_to(FALSE)
        ->text($text)
    ->send();
    return;
}elseif($telegram->text_command("me") && $telegram->words() > 1){
    $text = substr($telegram->text(), strlen("/me "));
    if(strpos($text, "/") !== FALSE){ exit(); }
    $joke = trim("*" .$telegram->user->first_name ."* " .$telegram->emoji($text));
}elseif($telegram->text_has("strtolower", TRUE) or $telegram->text_command("strtolower")){
    $text = ($telegram->has_reply && isset($telegram->reply->text) ? $telegram->reply->text : $telegram->text());
    $text = strtolower($text);
    $telegram->send
        ->notification(FALSE)
        ->text($text)
    ->send();
    return;
}elseif($telegram->text_has(["Cuéntame", "cuéntanos", "cuenta"], ["otro chiste", "un chiste"])){
    // TODO
    $this->analytics->event('Telegram', 'Games', 'Jokes');
    // $this->last_command("JOKE");

    if(
        true == false
        // $this->telegram->is_chat_group()
        // && $this->is_shutup_jokes()
    ){ return; }

    $joke = $this->pokemon->joke();

    if(filter_var($joke, FILTER_VALIDATE_URL) !== FALSE){
        // Foto
        $this->telegram->send
            ->notification( !$this->telegram->is_chat_group() )
            ->file('photo', $joke);
    }else{
        $this->telegram->send
            ->notification( !$this->telegram->is_chat_group() )
            ->text($joke, TRUE)
        ->send();
    }
    return;
}elseif($telegram->text_has("dame", ["un huevo", "pokeball", "pokeballs"]) && $telegram->words() <= 6){
	$joke = "Nope.";
}


if(!empty($joke)){
    $telegram->send
        ->notification(FALSE)
        ->text($joke, TRUE)
    ->send();

    exit();
}


?>
