<?php

namespace App\Livewire;

use Livewire\Component;
use Auth;
use App\Models\User;
use App\Models\Message;

class Chat extends Component
{
    public $userData;
    public $users;
    public $popup;
    public $chatActive;
    public $beta;
    public $lastMessages = [];
    public $dataMessages = [];

    public function mount($popup = false)
    {
        $this->popup = $popup;
        $this->beta = 1;

        $this->userData = [
            'name' => Auth::user()->name,
            'avatar' => Auth::user()->profile_photo_url,
            'id' => Auth::id(),
        ];

        $user = Auth::user();

        // Obtener todos los superadministradores (role_id = '1')
        $superAdmins = User::where('role_id', '1')->get();

        // Obtener amigos del usuario autenticado
        $friends = $user->friends;

        // Combinar superadministradores y amigos en una colección, priorizando superadministradores
        $this->users = $superAdmins->merge($friends);

        // Filtrar usuarios duplicados por id
        $this->users = $this->users->unique('id');

        // Reindexar la colección si es necesario
        $this->users = $this->users->values();

    }
    public function render()
    {
        $userDataJson = json_encode($this->userData);
        $this->dispatch('userDataUpdated', $userDataJson);

        return view('livewire.chat');
    }
    public function openChat($user_recipient_id)
    {

        $sender_id = Auth::id();

        $this->lastMessages[$user_recipient_id] = Message::where(function ($query) use ($sender_id, $user_recipient_id) {
            $query->where('sender_id', $sender_id)
                ->where('recipient_id', $user_recipient_id);
        })->orWhere(function ($query) use ($sender_id, $user_recipient_id) {
            $query->where('sender_id', $user_recipient_id)
                ->where('recipient_id', $sender_id);
        })->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->reverse();

        $this->dataMessages[$user_recipient_id] = [
            'sender_profile' => Auth::user()->profile_photo_url,
            'recipient_profile' => User::find($user_recipient_id)->profile_photo_url
        ];
        $this->chatActive[$user_recipient_id] = User::find($user_recipient_id);
        $this->dispatch("openedChat", $user_recipient_id);
    }
    public function removeChat($user_recipient_id)
    {
        unset($this->chatActive[$user_recipient_id]);
        $this->dispatch("closedChat", $user_recipient_id);
    }

    public function sendMessage($user_recipient_id, $message)
    {
        $user = User::find($user_recipient_id);
        // Guarda el mensaje en la base de datos o envíalo a través de socket.io
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $user->id,
            'content' => $message,
        ]);
        $sender_id = Auth::id();
        $this->lastMessages[$user_recipient_id] = Message::where(function ($query) use ($sender_id, $user_recipient_id) {
            $query->where('sender_id', $sender_id)
                ->where('recipient_id', $user_recipient_id);
        })->orWhere(function ($query) use ($sender_id, $user_recipient_id) {
            $query->where('sender_id', $user_recipient_id)
                ->where('recipient_id', $sender_id);
        })->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->reverse();

    }
}
