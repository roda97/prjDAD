<?php

namespace App\Policies;

use App\User;
use App\Movement;
use Illuminate\Auth\Access\HandlesAuthorization;

class MovementPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability){


    }

    /**
     * Determine whether the user can view the movimento.
     *
     * @param  \App\User  $user
     * @param  \App\Movement  $movement
     * @return mixed
     */
    public function view(User $user, Movement $Mmvement)
    {
        return true;
    }

   /* public function viewAll(User $user)
    {
        if($user->direcao==1){
            return true;
        }
        return false;
    }

    public function pesquisar(User $user)
    {
        if($user->tipo_socio=='P'){
            return true;
        }
        return false;
    }
*/
    /**
     *
     * Determine whether the user can create movimentos.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    /*public function create(User $user)
    {
        if($user->direcao==1 || $user->tipo_socio=='P'){
            return true;
        }
        return false;
    }*/

    /**
     * Determine whether the user can update the movimento.
     *
     * @param  \App\User  $user
     * @param  \App\Movement  $movement
     * @return mixed
     */
    /*public function update(User $user, Movement $movement)
    {
  
        if($movement->confirmado){
            return false;
        }
        if($user->direcao==1){
            return true;
        }
       if($user->id == $movement->piloto_id || $user->id == $movement->instrutor_id){
           return true;
       }
        return false;
    }*/

    /**
     * Determine whether the user can delete the movimento.
     *
     * @param  \App\User  $user
     * @param  \App\Movement  $movement
     * @return mixed
     */
    /*public function delete(User $user, Movement $movement)
    {
        if($movement->confirmado){
            return false;
        }
        if($user->direcao==1){
            return true;
        }
        if($user->id == $movement->piloto_id || $user->id == $movement->instrutor_id){
            return true;
        }
        return false;
    }
*/
    /**
     * Determine whether the user can restore the movimento.
     *
     * @param  \App\User  $user
     * @param  \App\Movement  $movement
     * @return mixed
     */
    public function restore(User $user, Movement $movement)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the movimento.
     *
     * @param  \App\User  $user
     * @param  \App\Movement  $movement
     * @return mixed
     */
    public function forceDelete(User $user, Movement $movement)
    {
        //
    }

    /*public function confirmar(User $user, Movement $movement)
    {

        if($movement->confirmado == 1){
            return false;
        }
        if($user->direcao==1){
            return true;
        }

        return false;
    }*/
}
