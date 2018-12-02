<?php
class Paging
{
    private $current_page; // 現在のページ
    private $num_per_page; // 1ページ当たりの件数
    private $pager_range;  // ページャーの数（範囲）
    private $total_count;  // 投稿の総件数

    public function __construct($page, $num_per_page, $pager_range, $total_count)
    {
        $this->current_page = $page;
        $this->num_per_page = $num_per_page;
        $this->pager_range = $pager_range;
        $this->total_count = $total_count;
    }
    
    public function getTotalPage()
    {
        return ceil($this->total_count / $this->num_per_page);
    }

    public function getPrevPage()
    {
        if($this->current_page > 1) {
            return $this->current_page - 1;
        }
    }

    public function getNextPage()
    {
        if($this->current_page < $this->getTotalPage()) {
            return $this->current_page + 1;
        }
    }

    public function getPagerRange()
    {
        //ページャー処理
        if($this->pager_range % 2 == 0) {
            $start = ($this->current_page-floor($this->pager_range/2) > 0) ? ($this->current_page-floor($this->pager_range/2)+1) : 1;//始点  
        } else {
            $start = ($this->current_page-floor($this->pager_range/2) > 0) ? ($this->current_page-floor($this->pager_range/2)) : 1;//始点
        }
        $end =  ($start > 1) ? ($this->current_page+floor($this->pager_range/2)) : $this->pager_range; // 終点
        $start = ($this->getTotalPage() < $end) ? $start-($end-$this->getTotalPage()):$start;// 始点再計算
        if($start < 1) {
            $start = 1;
        }
        $end = ($end > $this->getTotalPage()) ? $this->getTotalPage() : $end; // 終点再計算

        for($i=$start;$i<=$end;$i++) {
            $pager[] = $i;
        }
        return $pager;
    }
}