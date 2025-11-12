<?php
namespace SimpleDashboardPHP\Api\App\Http\Controllers;

use SimpleDashboardPHP\Core\Response;
use SimpleDashboardPHP\Pages\Examples\Projects\App\Models\Project;

class ProjectController
{
  public function getAll() {
    $projects = Project::all();
    return Response::json(["projects" => $projects])->send();
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