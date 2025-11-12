<?php
namespace SimpleDashboardPHP\Pages\Examples\Projects\App\Http\Controllers;

use SimpleDashboardPHP\Core\View;
use SimpleDashboardPHP\Pages\Examples\Projects\App\Models\Project;

class ProjectController
{
  public function index() {
    $projects = Project::all();

    return View::render("layouts/AppLayout", [
      "head" => ["title" => "Projects"],
      "body" => [
        "content" => "projects/ProjectsPage",
        "data" => [
          "sidebar" => [
            "activeNavLinkId" => "pages",
            "activeNavLinkItemId" => "pages_projects",
          ],
          "projects" => $projects
        ],
      ]
    ]);
  }

  public function show($projectId) {
    $project = Project::find($projectId);
    return View::render("layouts/AppLayout", [
      "head" => ["title" => "Project Detail"],
      "body" => [
        "content" => "projects/ProjectDetail",
        "data" => [
          "sidebar" => [
            "activeNavLinkId" => "pages",
            "activeNavLinkItemId" => "pages_projects",
          ],
          "project" => $project
        ]
      ]
    ]);
  }

  public function create() {
    return View::render("layouts/AppLayout", [
      "head" => ["title" => "Project Add"],
      "body" => [
        "content" => "projects/ProjectForm",
        "data" => [
          "sidebar" => [
            "activeNavLinkId" => "pages",
            "activeNavLinkItemId" => "pages_projects_add",
          ],
        ]
      ]
    ]);
  }

  public function edit($projectId) {
    $project = Project::find($projectId);
    return View::render("layouts/AppLayout", [
      "head" => ["title" => "Project Edit"],
      "body" => [
        "content" => "projects/ProjectForm",
        "data" => [
          "sidebar" => [
            "activeNavLinkId" => "pages",
            "activeNavLinkItemId" => "pages_projects",
          ],
          "project" => $project
        ]
      ]
    ]);
  }
}