<?php
require 'config.php';require_login();
$id=(int)($_GET['id']??0);$s=$conn->prepare('SELECT m.*,u.name host FROM meetings m JOIN users u ON u.id=m.host_id WHERE m.id=?');$s->bind_param('i',$id);$s->execute();$m=$s->get_result()->fetch_assoc();if(!$m)die('Meeting not found.');
if(me()['role']==='student' && $m['group_id'] && (int)$m['group_id']!==(int)me()['group_id'])die('You are not authorised for this meeting.');
if(me()['role']==='student' && $m['module_id']){$q=$conn->query('SELECT level_id,group_id FROM modules WHERE id='.(int)$m['module_id'])->fetch_assoc();if(!$q||(int)$q['level_id']!==(int)me()['level_id']||($q['group_id']&&(int)$q['group_id']!==(int)me()['group_id']))die('You are not authorised for this module meeting.');}
$url=getenv('LIVEKIT_URL')?:'';$key=getenv('LIVEKIT_API_KEY')?:'';$secret=getenv('LIVEKIT_API_SECRET')?:'';
function b64u($x){return rtrim(strtr(base64_encode($x),'+/','-_'),'=');}
$token='';
if($url&&$key&&$secret){$now=time();$header=b64u(json_encode(['alg'=>'HS256','typ'=>'JWT']));$payload=b64u(json_encode(['iss'=>$key,'sub'=>(string)me()['id'],'name'=>me()['name'],'nbf'=>$now,'exp'=>$now+3600,'video'=>['roomJoin'=>true,'room'=>$m['room_name'],'canPublish'=>true,'canSubscribe'=>true,'canPublishData'=>true]]));$sig=b64u(hash_hmac('sha256',"$header.$payload",$secret,true));$token="$header.$payload.$sig";}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($m['title'])?> · BetterCom</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="assets/style.css" rel="stylesheet"></head><body class="p-3"><div class="container"><a href="index.php?page=meetings">← Back to meetings</a><h2 class="mt-3"><?=e($m['title'])?></h2><p><?=e($m['description'])?></p><?php if(!$token):?><div class="alert alert-warning">LiveKit is not configured yet. Add LIVEKIT_URL, LIVEKIT_API_KEY and LIVEKIT_API_SECRET to Railway variables to enable live video/audio rooms.</div><?php else:?><div id="room" class="meeting-stage"></div><div class="d-flex gap-2 my-3"><button id="mic" class="btn btn-outline-primary">Toggle microphone</button><button id="cam" class="btn btn-outline-primary">Toggle camera</button><button id="share" class="btn btn-outline-primary">Share screen</button><button id="leave" class="btn btn-danger">Leave meeting</button></div><div id="status" class="text-muted">Connecting…</div><script type="module">
import {Room,RoomEvent,createLocalVideoTrack} from 'https://cdn.jsdelivr.net/npm/livekit-client/dist/livekit-client.esm.mjs';
const room=new Room({adaptiveStream:true,dynacast:true});const token=<?=json_encode($token)?>;const server=<?=json_encode($url)?>;
function tile(track,identity){const el=track.attach();el.autoplay=true;el.playsInline=true;el.style='width:260px;max-width:100%;border-radius:12px;background:#101c3c;margin:8px';const box=document.createElement('div');box.append(el);const label=document.createElement('small');label.textContent=identity;box.append(label);document.querySelector('#room').append(box);}
room.on(RoomEvent.TrackSubscribed,(track,pub,participant)=>tile(track,participant.identity));
room.on(RoomEvent.ParticipantConnected,p=>document.querySelector('#status').textContent=p.identity+' joined');
await room.connect(server,token);await room.localParticipant.enableCameraAndMicrophone();room.localParticipant.videoTrackPublications.forEach(p=>{if(p.track)tile(p.track,room.localParticipant.identity)});
document.querySelector('#status').textContent='Connected. Share the room link only with authorised participants.';
document.querySelector('#mic').onclick=()=>room.localParticipant.setMicrophoneEnabled(!room.localParticipant.isMicrophoneEnabled);
document.querySelector('#cam').onclick=()=>room.localParticipant.setCameraEnabled(!room.localParticipant.isCameraEnabled);
document.querySelector('#share').onclick=()=>room.localParticipant.setScreenShareEnabled(true);
document.querySelector('#leave').onclick=async()=>{await room.disconnect();location.href='index.php?page=meetings'};
</script><?php endif;?></div></body></html>
