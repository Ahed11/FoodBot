<?php

namespace App\Console\Commands;

use App\Models\Dish;
use Illuminate\Console\Command;

class FoodCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'food:create';

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
        $name = $this->ask("Введите название блюда");

        $options = [
            1 => 'Yes',
            2 => 'No'
        ];


        $i = 0;

        while($i == 0){
                $choice = array_search(
                    $this->choice("Вы уверены что хотите назвать блюдо: {$name}", $options, 1, null, false),
                    $options);

                if($choice == "1"){
                    $this->info("Отлично! Вы дали название: {$name} блюду");

                    $i = 1;
                } else{
                    $name = $this->ask("Введите новое имя!");
                }
            }

        $price = $this->ask("Введите цену блюда");


        $i = 0;


        while($i == 0){
            $choice = array_search(
                $this->choice("Вы уверены что хотите оценить {$name} в следующую цену: {$price}", $options, 1, null, false),
                $options);

            if($choice == 1){
                $this->info("Отлично! Вы оценили {$name} в {$price}");

                $i = 1;
            } else{
                $price = $this->ask("Введите новую цену!");
            }
        }

        Dish::create([
            'name' => $name,
            'price' => $price
            ]);

        $this->info("Вы создали блюдо {$name} с ценой {$price}");
    }
}
