<?php

namespace Modules\Email\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;


class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        $dateFormat = "d/m/y H:i";
        
        $lang = $request->get("lang");


        $res = parent::toArray($request);

        $attachments = [];


        if($att = $this->attachments){
            
            if(is_array($att))
            foreach($att as $id => $attachment){
                

                $attachments[] = [ 
                    "id" => $id, 
                    "url" => $attachment
                ];

                
            }

        }


      //  $sent = $this->sent ? optional($this->sent)->format($dateFormat);
       // $send_after = optional($this->send_after)->format($dateFormat);

        $sent = $this->sent instanceof Carbon ? $this->sent->format($dateFormat) : $this->sent;
     
       
        if($request->boolean("dateformat")){

           $this->send_after->format($dateFormat);

        }

       

        return array_merge($res,
        [
            "attachments" => $attachments,
            "sent" => $sent           
        ]);


    }


 

}
