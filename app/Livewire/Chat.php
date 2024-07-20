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
        switch ($user->role_id) {
            case '1':
                $this->users = User::where('id','!=',Auth::id())->get();
                break;
            
            default:
                
                $superAdmins = User::where('role_id', '1')->get();
                $friends = $user->friends;
                $this->users = $superAdmins->merge($friends);
                $this->users = $this->users->unique('id');
                $this->users = $this->users->values();
                break;
        }

        $this->users->transform(function ($user) {
            $user->unread_messages_count = Message::where('recipient_id', Auth::id())
                ->where('sender_id', $user->id)
                ->where('status', 'unread')
                ->count();
            return $user;
        });

    }
    public function render()
    {
        $userDataJson = json_encode($this->userData);
        $this->dispatch('userDataUpdated', $userDataJson);

        return view('livewire.chat');
    }
    public function openChat($user_code)
    {
        $user_recipient = User::where('user_code',$user_code)->first();
        $user_recipient_id = $user_recipient->id;

        $sender_id = Auth::id();

        // Actualizar estado de mensajes no leídos a read
        Message::where('sender_id', $user_recipient_id)
        ->where('recipient_id', $sender_id)
        ->where('status', 'unread')
        ->update(['status' => 'read']);

        $this->lastMessages[$user_code] = Message::where(function ($query) use ($sender_id, $user_recipient_id) {
            $query->where('sender_id', $sender_id)
                ->where('recipient_id', $user_recipient_id);
        })->orWhere(function ($query) use ($sender_id, $user_recipient_id) {
            $query->where('sender_id', $user_recipient_id)
                ->where('recipient_id', $sender_id);
        })->orderBy('created_at', 'desc')
            ->take(100)
            ->get()
            ->reverse();

        $this->dataMessages[$user_code] = [
            'sender_profile' => Auth::user()->profile_photo_url,
            'recipient_profile' => User::find($user_recipient_id)->profile_photo_url
        ];
        $this->chatActive[$user_recipient_id] = User::find($user_recipient_id)->getChatDetails();
        $this->dispatch("openedChat", $user_code);
    }
    public function removeChat($index)
    {
       
        unset($this->chatActive[$index]);
    }

    public function sendMessage($user_code, $message)
    {
        $user_recipient = User::where('user_code',$user_code)->first();
        $user_recipient_id = $user_recipient->id;
        // Guarda el mensaje en la base de datos o envíalo a través de socket.io
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $user_recipient_id,
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

            $this->dispatch("messageSaved", $user_code);

    }
}
