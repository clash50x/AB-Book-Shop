<?php
function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function redirect($url){ header("Location: $url"); exit; }
function money($n){ return 'Rs ' . number_format((float)$n, 0); }

function cart(){
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    return $_SESSION['cart'];
}
function cart_count(){
    $c=0; foreach(cart() as $q) $c += (int)$q; return $c;
}
function cart_add($pid,$qty=1){
    if(!isset($_SESSION['cart'])) $_SESSION['cart']=[];
    $pid=(int)$pid; $qty=max(1,(int)$qty);
    $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + $qty;
}
function cart_set($pid,$qty){
    $pid=(int)$pid; $qty=(int)$qty;
    if($qty<=0) unset($_SESSION['cart'][$pid]); else $_SESSION['cart'][$pid]=$qty;
}
function cart_remove($pid){ unset($_SESSION['cart'][(int)$pid]); }
function cart_clear(){ $_SESSION['cart']=[]; }

function flash_set($k,$v){ $_SESSION['_flash'][$k]=$v; }
function flash_get($k){ $v=$_SESSION['_flash'][$k]??null; unset($_SESSION['_flash'][$k]); return $v; }
