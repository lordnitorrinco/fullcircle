import tkinter as tk
from tkinter import ttk
from screens.home_screen import HomeScreen
from screens.php_screen import PhpScreen
from screens.laravel_screen import LaravelScreen
from screens.wordpress_screen import WordpressScreen
from screens.mysql_screen import MysqlScreen
from screeninfo import get_monitors

class FullCircleApp(tk.Tk):
    def __init__(self):
        super().__init__()
        self.title("Full Circle App")
        self.attributes('-fullscreen', True)  # Configurar pantalla completa
        self.center_window()

        self.configure(bg="#2e2e2e")

        style = ttk.Style(self)
        style.theme_use("clam")
        style.configure("TNotebook", background="#2e2e2e", foreground="#ffffff")
        style.configure("TNotebook.Tab", background="#3e3e3e", foreground="#ffffff", font=("Arial", 20))
        style.map("TNotebook.Tab", background=[("selected", "#1e1e1e")])

        self.create_layout()

    def center_window(self):
        self.update_idletasks()
        width = self.winfo_width()
        height = self.winfo_height()
        monitor = self.get_current_monitor()
        x = (monitor.width // 2) - (width // 2) + monitor.x
        y = (monitor.height // 2) - (height // 2) + monitor.y
        self.geometry(f'{width}x{height}+{x}+{y}')

    def get_current_monitor(self):
        # Obtener la posición del cursor
        cursor_x, cursor_y = self.winfo_pointerxy()
        # Encontrar el monitor en el que se encuentra el cursor
        for monitor in get_monitors():
            if monitor.x <= cursor_x <= monitor.x + monitor.width and monitor.y <= cursor_y <= monitor.y + monitor.height:
                return monitor
        return get_monitors()[0]  # Devolver el monitor principal si no se encuentra el cursor

    def create_layout(self):
        # Crear el frame para HomeScreen
        home_frame = HomeScreen(self, self)
        home_frame.place(relwidth=0.25, relheight=1.0)

        # Crear el frame para las pestañas
        tab_frame = tk.Frame(self, bg="#2e2e2e")
        tab_frame.place(relx=0.25, relwidth=0.75, relheight=1.0)

        self.tab_control = ttk.Notebook(tab_frame)
        self.frames = {}
        self.create_tabs()

    def create_tabs(self):
        self.check_containers()

    def check_containers(self):
        import docker
        client = docker.from_env()
        containers = {
            "php": ["php_container"],
            "laravel": ["laravel-app-1", "laravel-mysql-1"],
            "wordpress": ["wordpress-app-1", "wordpress-mysql-1"],
            "mysql": ["mysql_container"]
        }
        tabs = {
            "php": PhpScreen,
            "laravel": LaravelScreen,
            "wordpress": WordpressScreen,
            "mysql": MysqlScreen
        }
        for name, container_names in containers.items():
            if all(client.containers.get(container).status == "running" for container in container_names):
                if name not in self.frames:
                    frame = tabs[name](self.tab_control, self)
                    self.tab_control.add(frame, text=name.capitalize())
                    self.frames[name] = frame
            else:
                if name in self.frames:
                    frame = self.frames.pop(name)
                    self.tab_control.forget(frame)

        self.tab_control.pack(expand=1, fill="both")
        self.after(5000, self.check_containers)  # Actualizar cada 5 segundos

def main():
    app = FullCircleApp()
    app.mainloop()

if __name__ == "__main__":
    main()
