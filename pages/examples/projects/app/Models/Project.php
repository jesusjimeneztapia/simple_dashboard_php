<?php
namespace SimpleDashboardPHP\Pages\Examples\Projects\App\Models;

use SimpleDashboardPHP\Core\Database\Model;

class Project extends Model
{
  protected string $table = "projects";
  protected array $fillable = [
    "name",
    "description",
    "status",
    "client_company",
    "project_leader",
    "estimated_budget",
    "total_amount_spent",
    "estimated_project_duration"
  ];
}