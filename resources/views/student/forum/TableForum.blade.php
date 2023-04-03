<?php
    $user = Auth::guard('student')->user();

    if(isset($TopicID) && $TopicID != ''){
        $forum = DB::table('tblforum')->where([
            ['TopicID', $TopicID],
            ['CourseID', Session::get('CourseIDS')]
        ])->orderBy('DateTime')->get();

        //dd($forum);

        foreach($forum as $frm)
        {
            $datetime = "";
            $content = nl2br($frm->Content);
            $dt = strtotime($frm->DateTime);
            $ystd = strtotime("yesterday");
            if(date("dmY",$dt) == date("dmY")){
                $datetime = "Today, ".date("h:i a ",$dt);
            }
            elseif(date("dmY",$dt) == date("dmY",$ystd)){
                $datetime = "Yesterday, ".date("h:i a ",$dt);
            }
            else{
                $datetime = date("d/m/Y, h:i a ",$dt);
            }

    
            if($user->ic == $frm->UpdatedBy){
                $clrupby = "#43b51c";
                $usrnm = $user->name;
            }
            elseif($user->ic != $frm->UpdatedBy){
                $getlect = DB::table('students')->where('ic', $frm->UpdatedBy)->first();

                if($getlect == null)
                {
                    $getlect = DB::table('users')->where('ic', $frm->UpdatedBy)->first();
                }

                $usrnm = $getlect->name;
                $clrupby = "#00fcdf";

            }

        
            ?>
            <tr>
                <td width="20%">
                    <strong  class="hidden-sm-down"><a style="color: {{ $clrupby }};text-decoration: none" href="#">{!! wordwrap($usrnm,20,"<br>\n",TRUE); !!}</a></strong>
                </td>
                <td width="75%"><div width="100%" align="left">{!! $content !!}</div><div width="100%" align="right"><strong style="font-size: 15px;color: #43b51c"><i><?php echo $datetime;?></i></strong></div></td>
                <td width="5%">
                    <a href="ForumDB.php?crsid=&frmid=&tpcID=&DEL">
                        <img style="height: 35px;" src="images/bin.png" data-toggle="tooltip" title="Edit">
                    </a>
                </td>
            </tr>
            <?php

        }

    }else{
        ?>
        <tr>
            <td width="20%"></td>
            <td width="75%"></td>
            <td width="5%">
                   
            </td>
        </tr>


        <?php

    }
    ?>

