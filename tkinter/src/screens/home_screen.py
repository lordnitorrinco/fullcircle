import tkinter as tk
import docker

class HomeScreen(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller

        self.configure(bg="#2e2e2e")

        label = tk.Label(self, text="Docker containers", font=("Arial", 24), bg="#2e2e2e", fg="#ffffff")
        label.pack(pady=10, padx=10)

        self.docker_client = docker.from_env()
        self.container_frames = {}
        self.update_containers()

    def update_containers(self):
        container_names = [
            "mysql_container",
            "php_container",
            "wordpress-app-1",
            "wordpress-mysql-1",
            "laravel-app-1",
            "laravel-mysql-1"
        ]
        for name in container_names:
            try:
                container = self.docker_client.containers.get(name)
                status = container.status
            except docker.errors.NotFound:
                status = "not found"

            if name not in self.container_frames:
                frame = tk.Frame(self, bg="#2e2e2e")
                frame.pack(fill="x", pady=5)
                label = tk.Label(frame, text=name, font=("Arial", 20), bg="#2e2e2e", fg="#ffffff")
                label.pack(side="left", padx=10)
                status_indicator = tk.Canvas(frame, width=20, height=20, bg="#2e2e2e", highlightthickness=0)
                status_indicator.pack(side="right", padx=10)
                self.container_frames[name] = (frame, status_indicator)
            else:
                frame, status_indicator = self.container_frames[name]
                status_indicator.delete("all")

            if status == "running":
                status_indicator.create_oval(5, 5, 15, 15, fill="green")
            else:
                status_indicator.create_oval(5, 5, 15, 15, fill="red")

        self.after(5000, self.update_containers)  # Actualizar cada 5 segundos
