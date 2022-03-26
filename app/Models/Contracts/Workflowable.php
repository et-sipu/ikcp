<?php

namespace App\Models\Contracts;

interface Workflowable
{
    public function workflow_apply($transition, $workflow = null);

    public function workflow_can($transition, $workflow = null);

    public function workflow_transitions();

    public function transition($status);

    public function transitions();

    public function get_owner();

    public function get_receivers();

    public function get_workflowable_type();

    public function get_edit_url();

    public function get_local_edit_url();

    public function reached_minimum_print_state();
}
