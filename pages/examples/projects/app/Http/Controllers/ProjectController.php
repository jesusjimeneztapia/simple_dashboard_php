<?php
namespace Projects\App\Http\Controllers;

use Projects\App\Models\Project;
use Projects\Core\View;

class ProjectController
{
  public function index() {
    $projects = Project::all();

    return View::render("layouts/ProjectsLayout", [
      "head" => ["title" => "Projects"],
      "body" => [
        "content" => "ProjectsPage",
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
    return View::render("layouts/ProjectsLayout", [
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
    return View::render("layouts/ProjectsLayout", [
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
    return View::render("layouts/ProjectsLayout", [
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