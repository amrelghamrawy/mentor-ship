<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class createUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create user';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $user['name'] = $this->ask('name of the new user');
        $user['email'] = $this->ask('email of the new user');
        $user['password'] = $this->secret('password of the new user');
        $roleName = $this->choice('select role of the new user', ['admin', 'editor']);
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->info('Role not found');
            return -1;
        }
        $Validator = Validator::make($user,[
            'name'=>['required' , 'string' , 'max:255'],
            'email'=>['required' , 'email' , 'max:255' , 'unique:'.User::class],
            'password' => ['required', Password::defaults() ]
        ]);
        if($Validator->fails()){
            foreach($Validator->errors()->all() as $error){
                $this->error($error);
            }
            
            return -1 ; 
        }


        DB::transaction(function() use ($user, $role) {

                $user['password'] = Hash::make($user['password']);
                $newUser = User::create($user);
                $newUser->roles()->attach($role->id);
            }
        );

        $this->info('user ' . $user['email'] . ' created successfully');
        return 0;
    }
}
