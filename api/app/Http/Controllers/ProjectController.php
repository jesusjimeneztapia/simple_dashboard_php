<?php
namespace SimpleDashboardPHP\Api\App\Http\Controllers;

use SimpleDashboardPHP\Core\Response;
use SimpleDashboardPHP\Pages\Examples\Projects\App\Models\Project;

class ProjectController
{
  public function getAll($queryParams) {
    $page = $queryParams["page"] ?? null;
    $per_page = $queryParams["per_page"] ?? null;

    if (!isset($page) && !isset($per_page)) {
      $projects = Project::all();
      return Response::json(["projects" => $projects])->send();
    }

    $page = $page ? (int) $page : 1;
    $per_page = $per_page ? (int) $per_page : 10;

    $projects = Project::paginate($page, $per_page);
    return Response::json($projects)->send();
  }

  public function store($body) {
    $project = new Project($body);
    $project->save();
    return Response::json(["project" => $project->getAttributes()])->send();
  }

  public function update($projectId, $body) {
    $project = Project::find($projectId);
    $project->update($body);
    return Response::json(["project" => $project->getAttributes()])->send();
  }

  public function destroy($projectId) {
    $project = Project::find($projectId);
    $project->delete();
    return Response::text("")->status(204)->send();
  }
}