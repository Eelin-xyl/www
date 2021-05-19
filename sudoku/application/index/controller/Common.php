<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use think\request;

class Common extends Controller
{
    public function start()
    {
        $num = Session::get('num');
        $this->assign('num', $num);
        return $this->fetch('/common/alpha');
    }

    public function choose()
    {
        $num = Session::get('num');
        $key = Request::instance()->param('key');
        Session::set('key', $key);

        for ($i = 1; $i <= 9; $i++)
        {
            $h1[$i] = '';
            $h2[$i] = '';
        }

        $x = $key % 9;
        $y = floor($key / 9);

        if ($x == 0) 
        {
            $x = 9;
        }
        else
        {
            $y++;        
        }

        for ($j = 1; $j <= 8; $j++)
        {
            $q = $x - $j;
            $p = $x + $j;
            $a = $y - $j;
            $b = $y + $j;

            if ($q >= 1)
            {
                $k1 = $num[$key - $j];
                if ($k1 != '*')
                {
                    $h1[$k1] = '<!--';
                    $h2[$k1] = '-->';
                }
            }

            if ($p <= 9)
            {
                $k2 = $num[$key + $j];
                if ($k2 != '*')
                {
                    $h1[$k2] = '<!--';
                    $h2[$k2] = '-->';
                }
            }

            if ($a >= 1)
            {
                $k3 = $num[$key - $j*9];
                if ($k3 != '*')
                {
                    $h1[$k3] = '<!--';
                    $h2[$k3] = '-->';
                }
            }

            if ($b <= 9)
            {
                $k4 = $num[$key + $j*9];
                if ($k4 != '*')
                {
                    $h1[$k4] = '<!--';
                    $h2[$k4] = '-->';
                }
            }
        }

        $x1 = $x % 3;
        $y1 = $y % 3;
        if ($x1 == 0) { $x1 = 3; }
        if ($y1 == 0) { $y1 = 3; }

        for ($j = 1; $j <=2; $j++)
        {
            $q = $x1 - $j;
            $p = $x1 + $j;
            $a = $y1 - $j;
            $b = $y1 + $j;

            if ($q >= 1 && $a >= 1)
            {
                $k5 = $num[$key - $j*10];
                if ($k5 != '*')
                {
                    $h1[$k5] = '<!--';
                    $h2[$k5] = '-->';
                }
            }

            if ($p <= 3 && $a >= 1)
            {
                $k6 = $num[$key - $j*8];
                if ($k6 != '*')
                {
                    $h1[$k6] = '<!--';
                    $h2[$k6] = '-->';
                }
            }

            if ($q >= 1 && $b <= 3)
            {
                $k7 = $num[$key + $j*8];
                if ($k7 != '*')
                {
                    $h1[$k7] = '<!--';
                    $h2[$k7] = '-->';
                }
            }

            if ($p <= 3 && $b <= 3)
            {
                $k8 = $num[$key + $j*10];
                if ($k8 != '*')
                {
                    $h1[$k8] = '<!--';
                    $h2[$k8] = '-->';
                }
            }
        }

        if ($key % 10 == 1)
        {
            for ($j = 10; $j <= 80; $j+=10)
            {
                if ($key - $j >= 1)
                {
                    $k9 = $num[$key - $j];
                    if ($k9 != '*')
                    {
                        $h1[$k9] = '<!--';
                        $h2[$k9] = '-->';
                    }
                }

                if ($key + $j <= 81)
                {
                    $k10 = $num[$key + $j];
                    if ($k10 != '*')
                    {
                        $h1[$k10] = '<!--';
                        $h2[$k10] = '-->';
                    }
                }
            }
        }

        if ($key % 8 == 1)
        {
            for ($j = 8; $j <= 64; $j+=8)
            {
                if ($key - $j >= 9)
                {
                    $k11 = $num[$key - $j];
                    if ($k11 != '*')
                    {
                        $h1[$k11] = '<!--';
                        $h2[$k11] = '-->';
                    }
                }

                if ($key + $j <= 73)
                {
                    $k12 = $num[$key + $j];
                    if ($k12 != '*')
                    {
                        $h1[$k12] = '<!--';
                        $h2[$k12] = '-->';
                    }
                }
            }
        }

        $h1[$num[$key]] = '';
        $h2[$num[$key]] = '';
        $this->assign(['num' => $num, 'h1' => $h1, 'h2' => $h2]);
    	return $this->fetch('/common/beta');
    }

    public function alter()
    {
        $num = Session::get('num');
        $key = Session::get('key');
        $value = Request::instance()->param('value');
        $num[$key] = $value;
        Session::set('num', $num);
        $this->assign('num', $num);
        $n = 0;

        for ($i = 1; $i <= 81; $i++)
        {
            if ($num[$i] != '*')
            {
                $n++;
            }
        }

        if ($n == 81)
        {
            return $this->fetch('/common/gamma');
        }
        
        return $this->fetch('/common/alpha');
    }

    public function finish()
    {
        $num = Session::get('num');
        Session::set('renum', $num);

        for ($i = 1; $i <= 81; $i++)
        {
            if ($num[$i] == '*')
            {
               $anum[$i] = '*'; 
            }
            else
            {
                $anum[$i] = $num[$i] + 10;
            }
        }

        $this->assign('num', $anum);
        return $this->fetch('/common/delta');
    }

    public function restart()
    {
        for ($i = 1; $i <= 81; $i++)
        {
        	$num[$i] = '*';
        }

        Session::set('num', $num);
        Session::delete('key');
        $this->assign('num', $num);
        return $this->fetch('/common/alpha');
    }
}