<?php

namespace Give\Campaigns\ListTable;

use Give\Campaigns\ListTable\Columns\DonationsCountColumn;
use Give\Campaigns\ListTable\Columns\GoalColumn;
use Give\Campaigns\ListTable\Columns\IdColumn;
use Give\Campaigns\ListTable\Columns\RevenueColumn;
use Give\Campaigns\ListTable\Columns\StatusColumn;
use Give\Campaigns\ListTable\Columns\TitleColumn;
use Give\Framework\ListTable\ListTable;
use Give\Framework\ListTable\ModelColumn;

/**
 * @since 4.0.0
 */
class CampaignsListTable extends ListTable
{
    /**
     * @since 4.0.0
     */
    public function id(): string
    {
        return 'campaigns';
    }

    /**
     * @since 4.0.0
     *
     * @return array|ModelColumn[]
     */
    protected function getDefaultColumns(): array
    {
        // TODO We need to decide which columns should be displayed
        return [
            new IdColumn(),
            new TitleColumn(),
            new GoalColumn(),
            new DonationsCountColumn(),
            new RevenueColumn(),
            //new DescriptionColumn(),
            //new DonationsCountColumn(),
            //new StartDateColumn(),
            //new EndDateColumn(),
            new StatusColumn(),
        ];
    }

    /**
     * @since 4.0.0
     *
     * @return array|string[]
     */
    protected function getDefaultVisibleColumns(): array
    {
        return [
            IdColumn::getId(),
            TitleColumn::getId(),
            GoalColumn::getId(),
            DonationsCountColumn::getId(),
            RevenueColumn::getId(),
            //DescriptionColumn::getId(),
            //DonationsCountColumn::getId(),
            //StartDateColumn::getId(),
            //EndDateColumn::getId(),
            StatusColumn::getId(),
        ];
    }
}
