<table class="table table-striped projects" id="projects-table">
  <thead>
    <tr>
      <th style="width: 1%">#</th>
      <th style="width: 20%">Project Name</th>
      <th style="width: 30%">Team Members</th>
      <th>Project Progress</th>
      <th style="width: 8%" class="text-center">Status</th>
      <th style="width: 20%"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($projects as $i => $project): ?>
      <?php
        $status = $project["status"];
        $badgeColor = "badge-secondary";
        if ($status == "Success") {
          $badgeColor = "badge-success";
        } else if ($status == "Canceled") {
          $badgeColor = "badge-danger";
        }
      ?>
      <tr data-id="<?= $project["id"] ?>">
        <td><?= $i + 1 ?></td>
        <td>
          <a><?= $project["name"] ?></a>
          <br />
          <small> Created 01.01.2019 </small>
        </td>
        <td>
          <ul class="list-inline">
            <li class="list-inline-item">
              <img
                alt="Avatar"
                class="table-avatar"
                src="../../../dist/img/avatar.png"
              />
            </li>
            <li class="list-inline-item">
              <img
                alt="Avatar"
                class="table-avatar"
                src="../../../dist/img/avatar2.png"
              />
            </li>
            <li class="list-inline-item">
              <img
                alt="Avatar"
                class="table-avatar"
                src="../../../dist/img/avatar3.png"
              />
            </li>
            <li class="list-inline-item">
              <img
                alt="Avatar"
                class="table-avatar"
                src="../../../dist/img/avatar4.png"
              />
            </li>
          </ul>
        </td>
        <td class="project_progress">
          <div class="progress progress-sm">
            <div
              class="progress-bar bg-green"
              role="progressbar"
              aria-valuenow="57"
              aria-valuemin="0"
              aria-valuemax="100"
              style="width: 57%"
            ></div>
          </div>
          <small> 57% Complete </small>
        </td>
        <td class="project-state">
          <span class="badge <?= $badgeColor ?>"><?= $project["status"] ?></span>
        </td>
        <td class="project-actions text-right">
          <a class="btn btn-primary btn-sm" href="<?= base_url() . "/detail/" . $project["id"] ?>">
            <i class="fas fa-folder"> </i>
            View
          </a>
          <a class="btn btn-info btn-sm" href="./edit/<?= $project["id"] ?>">
            <i class="fas fa-pencil-alt"> </i>
            Edit
          </a>
          <button class="btn btn-danger btn-sm" onclick="deleteProjectById(<?= $project['id'] ?>)">
            <i class="fas fa-trash"> </i>
            Delete
          </button>
        </td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>

<script>
  const API_BASE = "<?= base_url() . "/api" ?>"

  const $projectsTable = document.querySelector("#projects-table")

  const deleteProjectById = async (projectId) => {
    const response = await fetch(`${API_BASE}/${projectId}`, {
      method: "DELETE"
    })
    if (response.ok) {
      $tBody = $projectsTable.querySelector("tbody")
      $projectRow = $tBody.querySelector(`tr[data-id="${projectId}"]`)
      if ($projectRow != null) {
        $projectRow.remove()
      }
    }
  }
</script>