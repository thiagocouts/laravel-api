<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \App\Http\Resources\Room;

class Student extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'birth' => $this->birth,
            'genre' => $this->genre,
            'room' => new Room($this->room),
            'links' => [
                [
                    'type' => 'GET',
                    'rel'  => 'show',
                    'url'  => route('students.show', $this->id)
                ],

                [
                    'type' => 'PUT',
                    'rel'  => 'update',
                    'url'  => route('students.update', $this->id)
                ],

                [
                    'type' => 'DELETE',
                    'rel'  => 'destroy',
                    'url'  => route('students.destroy', $this->id)
                ]
            ]
        ];
    }
}
