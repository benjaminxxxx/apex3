<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'content',
        'status',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    // Método para marcar un mensaje como leído
    public function markAsRead()
    {
        $this->status = 'read';
        $this->save();
    }

    // Método para obtener mensajes no leídos del usuario destinatario
    public static function unreadMessagesForRecipient($recipientId)
    {
        return self::where('recipient_id', $recipientId)
            ->where('status', 'unread')
            ->get();
    }
}
