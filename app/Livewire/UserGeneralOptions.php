<?php

namespace App\Livewire;

use App\Models\Friendship;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Database\QueryException;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class UserGeneralOptions extends Component
{
    use LivewireAlert;
    public $userToOperate;
    protected $listeners = ['askDisableUser','askEnableUser','askDeleteUser','disableUser','enableUser','deleteUser'];

    public function render()
    {
        return view('livewire.user-general-options');
    }
    public function askEnableUser($userId)
    {
        $this->userToOperate = $userId;

        $this->alert('question', '¿Está seguro(a) que desea Habilitar este usuario?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'Si, Habilitar',
            'cancelButtonText' => 'Cancelar',
            'onConfirmed' => 'enableUser',
            'showCancelButton' => true,
            'position' => 'center',
            'toast' => false,
            'timer' => null,
            'confirmButtonColor' => '#0E7490', // Esto sobrescribiría la configuración global
            'cancelButtonColor' => '#2C2C2C',
        ]);
    }
    
    public function askDisableUser($userId)
    {
        $this->userToOperate = $userId;

        $this->alert('question', '¿Está seguro(a) que desea Deshabilitar este usuario?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'Si, Deshabilitar',
            'cancelButtonText' => 'Cancelar',
            'onConfirmed' => 'disableUser',
            'showCancelButton' => true,
            'position' => 'center',
            'toast' => false,
            'timer' => null,
            'confirmButtonColor' => '#0E7490', // Esto sobrescribiría la configuración global
            'cancelButtonColor' => '#2C2C2C',
        ]);
    }
    public function askDeleteUser($userId)
    {
        $this->userToOperate = $userId;

        $this->alert('question', '¿Está seguro(a) que desea Eliminar este usuario?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'Si, Eliminar',
            'cancelButtonText' => 'Cancelar',
            'onConfirmed' => 'deleteUser',
            'showCancelButton' => true,
            'position' => 'center',
            'toast' => false,
            'timer' => null,
            'confirmButtonColor' => '#0E7490', // Esto sobrescribiría la configuración global
            'cancelButtonColor' => '#2C2C2C',
        ]);
    }
    public function disableUser()
    {
        if(!$this->userToOperate){
            return;
        }

        $user = User::findOrFail($this->userToOperate);
        $user->status = '0';
        $user->save();

        if (config('session.driver') === 'database') {

            DB::connection(config('session.connection'))
                ->table(config('session.table', 'sessions'))
                ->where('user_id',$this->userToOperate)
                ->delete();
        }
        $this->dispatch('userModified');

        $this->userToOperate = null;
    }
    public function enableUser()
    {
        if(!$this->userToOperate){
            return;
        }
        $user = User::findOrFail($this->userToOperate);
        $user->status = '1';
        $user->save();
        $this->dispatch('userModified');

        $this->userToOperate = null;
    }
    public function deleteUser()
    {
        if ($this->userToOperate) {
            try {
                $user = User::find($this->userToOperate);

                Friendship::where('user_id', Auth::id())->where('friend_id', $user->id)->delete();
                Friendship::where('user_id', $user->id)->where('friend_id', Auth::id())->delete();

                $user->delete();

                $this->alert('success', 'Usuario eliminado correctamente.');
                $this->dispatch('userModified');
            } catch (QueryException $e) {
                // Manejo de la excepción de consulta
                $this->alert('error', 'No se pudo eliminar el usuario. Asegúrate de que no tenga dependencias.' . $e->getMessage());
            } catch (\Exception $e) {
                // Manejo de otras excepciones
                $this->alert('error', 'Ocurrió un error al eliminar el usuario.' . $e->getMessage());
            }
        }
        $this->userToOperate = null;
    }
}
