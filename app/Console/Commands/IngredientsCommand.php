<?php

namespace App\Console\Commands;

use App\Models\Dish;
use App\Models\ingredients;
use Illuminate\Console\Command;

class IngredientsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ingredients:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $options = [
            1 => 'Yes',
            2 => 'No'
        ];

        $spices = [
            1 => "pepper",
            2 => "salt",
            3 => "sugar",
            4 => "ginger",
            5 => "turmeric",//kurkum
            6 => "coriander"//kezbara
        ];

        $cereals = [
            1 => "rice",
            2 => "burgul",
            3 => "buckwheat"//hinta
        ];

        $legumes = [
            1 => "bean",//fasulia
            2 => "chickpeas",//hummus
            3 => "lentil",//adas
            4 => "peas",//bazela
            5 => "soy"
        ];

        $kindsOfIngredients = [
            1 => "spices",
            2 => "cereals",
            3 => "legumes"
        ];

        $dishArray = [];

        $dishes = Dish::get()->all();

        foreach ($dishes as $dish) {
            $dishArray[$dish->id] = $dish->name;
        }

        $i = 0;

        while($i == 0){
            $choice_dish = array_search(
                $this->choice("Выберите блюдо к которому хотите добавить ингредиент:",$dishArray,1,null,false),
                $dishArray
            );

            $confirmation_dish = array_search(
                $this->choice("Вы уверены что хотите выбрать {$dishArray[$choice_dish]} для добавления ингредиента",$options,1,null,false),
                $options
            );

            if($confirmation_dish == "1"){
                $this->info("Отлично! Вы выбрали {$dishArray[$choice_dish]}");

                $i = 1;
            } else{
                $name = $this->ask("Выберите блюдо!");
            }
        }

        $i = 0;

        while($i == 0){
            $choice_KOI = array_search(
                $this->choice("Выберите категорию из которой хотите добавить ингредиент к блюду {$dishArray[$choice_dish]}:",$kindsOfIngredients, 1, null, false),
                $kindsOfIngredients);

            $confirmation_KOI = array_search(
                $this->choice("Вы уверены что хотите выбрать категория {$kindsOfIngredients[$choice_KOI]} для {$dishArray[$choice_dish]}",$options,1,null,false),
                $options
            );

            if($confirmation_KOI == "1"){
                $this->info("Отлично! Вы выбрали {$kindsOfIngredients[$choice_KOI]}");

                $i = 1;
            } else{
                $name = $this->ask("Выберите ктегорию ингредиентов!");
            }
        }

        switch($choice_KOI){
            case "1":{
                $choice_spices = array_search(
                    $this->choice("Выберите специю, которую\n хотите добавить к {$dishArray[$choice_dish]}",$spices,1,null,false),
                    $spices
                );

                $confirmation_spices = array_search(
                    $this->choice("Вы уверены что хотите\n выбрать специю {$spices[$choice_spices]} для {$dishArray[$choice_dish]}",$options,1,null,false),
                    $options
                );

                $i = 0;

                while($i == 0){
                    if($confirmation_spices == "1"){
                        $this->info("Отлично! Вы выбрали {$spices[$choice_spices]}");

                        $i = 1;
                    } else{
                        $name = $this->ask("Выберите ингредиент!");
                    }
                }

                ingredients::create([
                    "dish_id" => Dish::where("name", $dishArray[$choice_dish])->firstOrFail()->id,
                    "name" => $spices[$choice_spices]
                ]);

                $this->info("Вы добавили к блюду {$dishArray[$choice_dish]} ингредиент {$spices[$choice_spices]}");

                break;
            }

            case "2":{
                $choice_cereals = array_search(
                    $this->choice("Выберите какую из злак\n хотите добавить к {$dishArray[$choice_dish]}",$cereals,1,null,false),
                    $cereals
                );

                $confirmation_cereals = array_search(
                    $this->choice("Вы уверены что хотите\n выбрать этот вид злак: {$cereals[$choice_cereals]} для {$dishArray[$choice_dish]}",$options,1,null,false),
                    $options
                );

                $i = 0;

                while($i == 0){
                    if($confirmation_cereals == "1"){
                        $this->info("Отлично! Вы выбрали {$cereals[$choice_cereals]}");

                        $i = 1;
                    } else{
                        $name = $this->ask("Выберите ингредиент!");
                    }
                }

                ingredients::create([
                    "dish_id" => Dish::where("name", $dishArray[$choice_dish])->firstOrFail()->id,
                    "name" => $cereals[$choice_cereals]
                ]);

                $this->info("Вы добавили к блюду {$dishArray[$choice_dish]} ингредиент {$cereals[$choice_cereals]}");

                break;
            }

            case "3":{
                $choice_legumes = array_search(
                    $this->choice("Выберите бобы, которые\n хотите добавить к {$dishArray[$choice_dish]}",$legumes,1,null,false),
                    $legumes
                );

                $confirmation_legumes = array_search(
                    $this->choice("Вы уверены что хотите\n выбрать эти бобы: {$legumes[$choice_legumes]} для {$dishArray[$choice_dish]}",$options,1,null,false),
                    $options
                );

                $i = 0;

                while($i == 0){
                    if($confirmation_legumes == "1"){
                        $this->info("Отлично! Вы выбрали {$legumes[$choice_legumes]}");

                        $i = 1;
                    } else{
                        $name = $this->ask("Выберите ингредиент!");
                    }
                }

                ingredients::create([
                    "dish_id" => Dish::where("name", $dishArray[$choice_dish])->firstOrFail()->id,
                    "name" => $legumes[$choice_legumes]
                ]);

                $this->info("Вы добавили к блюду {$dishArray[$choice_dish]} ингредиент {$legumes[$choice_legumes]}");

                break;
            }
        }
    }
}
