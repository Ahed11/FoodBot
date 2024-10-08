<?php

    namespace App\Http\Controllers;
    use App\Models\Dish;
    use App\Models\ingredients;
    use DefStudio\Telegraph\Handlers\WebhookHandler;
    use DefStudio\Telegraph\Keyboard\Button;
    use DefStudio\Telegraph\Keyboard\Keyboard;
    use DefStudio\Telegraph\Models\TelegraphBot;
    use DefStudio\Telegraph\Models\TelegraphChat;
    use Illuminate\Support\Facades\Http;

    class Handler extends WebhookHandler{

        public function start(){
            $bot = TelegraphBot::where('id', 1)->firstOrFail();
            $requestArray = $this->request->toArray();

            $chatId = $requestArray['message']['from']['id'];
            $name = $requestArray['message']['from']['first_name'];

            if(TelegraphChat::where('chat_id', $chatId)->exists()){
                $chatBot = TelegraphChat::where('chat_id', $chatId)->firstOrFail();

                $chatBot->message("hello {$name}!")->Keyboard(Keyboard::make()->buttons([
                    Button::make("show all dishes")->action("show_dishes")
                ]))->send();

                return;
            }

            $chat = $bot->chats()->create([
                "chat_id"=> $chatId,
                "name" => $name
            ]);

            $chat->message("hello {$name}!")->Keyboard(Keyboard::make()->buttons([
                Button::make("show all dishes")->action("show_dishes")
            ]))->send();
        }

        public function show_dishes(){
            $bot = TelegraphBot::where("id",1)->firstOrFail();
            $requestArray = $this->request->toArray();

            $chatId = $requestArray["callback_query"]["from"]["id"];

            $chatBot = TelegraphChat::where("chat_id", $chatId)->firstOrFail();

            $dishes = Dish::get()->all();

            foreach($dishes as $d){
                $chatBot->message("{$d->name}")->Keyboard(Keyboard::make()->buttons([
                    Button::make("show {$d->name}")->action("show_dish")->param("id", $d->id)
                ]))->send();
            }

            $this->reply("");
        }

        public function show_dish(){
            $bot = TelegraphBot::where("id",1)->firstOrFail();
            $requestArray = $this->request->toArray();

            $chatId = $requestArray["callback_query"]["from"]["id"];

            $chatBot = TelegraphChat::where("chat_id", $chatId)->firstOrFail();

            $dishId = $this->data->get("id");

            $dish = Dish::where("id", $dishId)->firstOrFail();
            $dishName = $dish->name;
            $dishPrice = $dish->price;

            $ingredientsName = ingredients::where("dish_id", $dishId)->get()->pluck("name");

            if($ingredientsName != null){
                $dishIngredients = "";

                foreach($ingredientsName as $value){
                    $dishIngredients = "{$dishIngredients}" . "\n" . $value;
                }

                $chatBot->message("*Food name*: {$dishName}\n*Food price*: {$dishPrice}\n*Food ingredients*: $dishIngredients")->Keyboard(Keyboard::make()->buttons([
                    Button::make("Buy {$dishName} for {$dishPrice}")->action("buy_dish")->param("id", $dishId)
                ]))->send();

                $this->reply("");
            }else{
                $chatBot->message("*Food name*: {$dishName}\n*Food price*: {$dishPrice}\n")->Keyboard(Keyboard::make()->buttons([
                    Button::make("Buy {$dishName} for {$dishPrice}")->action("buy_dish")->param("id", $dishId)
                ]))->send();

                $this->reply("");
            }


        }

        public function buy_dish(){
            $bot = TelegraphBot::where("id",1)->firstOrFail();
            $requestArray = $this->request->toArray();

            $chatId = $requestArray["callback_query"]["from"]["id"];

            $chatBot = TelegraphChat::where("chat_id", $chatId)->firstOrFail();

            $dishId = $this->data->get("id");

            $dish = Dish::where("id", $dishId)->firstOrFail();
            $dishName = $dish->name;

            $chatBot->message("You bought a $dishName")->send();

            $this->reply("");
        }

        public static function sendInvoice(string $title, string $description, int $amount){
            $bot = TelegraphBot::where("id",1)->firstOrFail();

            $botToken = $bot->token;

            $token = env("PAY_MASTER_TOKEN");

            // $requestArray = $this->request->toArray();

            // $chatId = $requestArray["callback_query"]["from"]["id"];

            $payload = \Str::uuid();

            $currency = "RUB";

            $url = "https://api.telegram.org/bot{$botToken}/sendInvoice";

            $respone = Http::post($url, [
                "chat_id" => 5134069019,
                "title" => $title,
                "description"=> $description,
                "payload" => $payload,
                "currency" => $currency,
                "prices" => [
                    [
                        "label" => "price",
                        "amount" => $amount,
                    ]
                    ],
                "provider_token" => $token
            ]);

            return $respone->json();
        }
    }
