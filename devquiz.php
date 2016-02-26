<?php

//I'm using composer for loading Tumblr SDK.
require "vendor/autoload.php";

//Get all slack params sent with slash command.
$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
$command = filter_input(INPUT_POST, 'command', FILTER_SANITIZE_STRING);
$text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
$channel_id = filter_input(INPUT_POST, 'channel_id', FILTER_SANITIZE_STRING);
$user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
$user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_STRING);

//get it from from slash command configuration in Slack.
if ($token !== "4f8uLdSjrKGQX4A8uXwsmZms") {
    http_response_code(403);
    exit;
}

//create slack webhook and paste its url here.
//$slack_webhook_url = "https://hooks.slack.com/services/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";

//this is bojinkata's beautiful head.
//$icon_url = "http://www4.pictures.zimbio.com/gi/AC+Chievo+Verona+v+Lecce+Serie+DuNWqy4FrZDm.jpg";

// //this script can be used for more than one slash commands that's why I use switch. I will add more shit later.
// switch ($command) {
//     case '/quiz':{
//         //FROM HERE: it's tumblr specific stuff for getting image. Don't steal my ACCESS_TOKENS ;)
//     $limit = 50;
//     $client = new Tumblr\API\Client(
//           '5WKMJM2Ya3guPDm3Qt0ixS99ZM4Zuy2sSRbdltL0sNrHJ5NFDC',
//           'oTa3cFlF60ApnXNfigocmnBBtoyd5QACyt2x9TDI18EsId1ZSR',
//           'Z3G2F6vEbuMB82V3lmlg820tp2w70LKGij81sJLLUhoBXVeoGL',
//           'rRwiHDlbJZH8WNcRX2rM1HEJbM2cbsqbXYCKu9FhpxxKrzqEby'
//     );

//     $offset = rand(0, (($client->getUserInfo()->user->likes-1)-$limit));

//     $result = $client->getLikedPosts(
//         array(
//             'limit' => $limit,
//             'offset' => $offset
//         )
//     );

//     $posts = $result->liked_posts;
//     $index = rand(0,(count($posts)-1));

     $message_text = "<Здравей @".$user_id."|".$user_name."> призовавам те на Quiz дуел! Ако имаш играта цъкни тук (devquiz://play). Ако я нямаш, свали я от тук(https://www.dropbox.com/s/hsn6ow3b8lqt9u1/app-debug.apk?dl=0).\n";
     //$message_text .= $posts[$index]->photos[0]->alt_sizes[0]->url;

        //TO HERE: is the Tumblr stuff. You can change it with whatever your slash command should do.


        //Here's the tricky part. This array holds the response that is sent to the webhook, which posts it as a Slack bot. In this case I'm sending Bot's name, icon, the channel I want to post in, the message and if I use markdown syntax in my text or not.
    $data = array(
        "username" => "DevQuizBot",
        "channel" => $channel_id,
        "text" => $message_text,
        "mrkdwn" => true,
        "icon_url" => $icon_url
    );

        //magically converts it to json ;)
    $json_string = json_encode($data);

        //prepares and sends post request to slack's webhook to post the bot's reply.
    $slack_call = curl_init($slack_webhook_url);
    curl_setopt($slack_call, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($slack_call, CURLOPT_POSTFIELDS, $json_string);
    curl_setopt($slack_call, CURLOPT_CRLF, true);
    curl_setopt($slack_call, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($slack_call, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Content-Length: " . strlen($json_string))
    );
    $result = curl_exec($slack_call);
    curl_close($slack_call);

        //This is not required, but it's essential for Slack's slash command, because it's the response that you get when executing it. In my case I want to greet the guy who printed a ticket to Belgrad!
    //echo "Така ве!";
    die;
  };
  break;
}