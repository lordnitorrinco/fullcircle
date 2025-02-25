import tkinter as tk
import subprocess

class MysqlScreen(tk.Frame):
    def __init__(self, parent, controller):
        super().__init__(parent)
        self.controller = controller

        self.configure(bg="#2e2e2e")

        label = tk.Label(self, text="MySQL Screen", font=("Arial", 24), bg="#2e2e2e", fg="#ffffff")
        label.pack(pady=10, padx=10)

        command_frame = tk.Frame(self, bg="#2e2e2e")
        command_frame.pack(pady=10, padx=10, side=tk.TOP, fill=tk.X)

        command_label = tk.Label(command_frame, text="Select Query", font=("Arial", 20), bg="#2e2e2e", fg="#ffffff")
        command_label.pack(side=tk.LEFT, padx=10)

        self.command_var = tk.StringVar(self)
        self.command_var.set("question1")  # Comando por defecto

        commands = ["question1", "question2", "question3"]

        self.command_menu = tk.OptionMenu(command_frame, self.command_var, *commands, command=self.update_options)
        self.command_menu.config(bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        self.command_menu["menu"].config(bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        self.command_menu.pack(side=tk.LEFT, padx=10)

        self.run_button = tk.Button(command_frame, text="Run Command", command=self.run_command, bg="#3e3e3e", fg="#ffffff", font=("Arial", 20))
        self.run_button.pack(side=tk.LEFT, padx=10)

        self.terminal = tk.Text(self, wrap=tk.WORD, width=80, height=20, bg="#1e1e1e", fg="#ffffff", insertbackground="#ffffff", font=("Courier", 20), padx=10, pady=10)
        self.terminal.pack(pady=10, padx=10)
        self.terminal.config(state=tk.DISABLED)  # Deshabilitar la entrada directa

    def update_options(self, *args):
        # Actualizar opciones si es necesario
        pass

    def run_command(self):
        command = self.command_var.get()
        if command == "question1":
            sql_commands = [
                ["USE rooms_db;", "SHOW TABLES;"]
            ]
        elif command == "question2":
            sql_commands = [
                ["USE rooms_db;", "CALL GetAvailableRooms(CURDATE());"]
            ]
        elif command == "question3":
            sql_commands = [
                ["USE products_db;", "CALL GetTop5BestSellingProducts();"],
                ["USE products_db;", "CALL GetTotalRevenueLast30Days();"]
            ]
        else:
            sql_commands = []

        self.execute_sql_commands(sql_commands)

    def execute_sql_commands(self, sql_commands):
        self.terminal.config(state=tk.NORMAL)
        self.terminal.delete(1.0, tk.END)  # Limpiar la consola
        try:
            for command_group in sql_commands:
                process = subprocess.Popen(
                    ["docker", "exec", "-i", "mysql_container", "mysql", "-uroot", "-proot"],
                    stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True
                )
                for command in command_group:
                    process.stdin.write(f"{command}\n")
                process.stdin.flush()
                stdout, stderr = process.communicate()
                output = stdout + stderr
                # Filtrar la advertencia de seguridad
                output = "\n".join(line for line in output.splitlines() if "Using a password on the command line interface can be insecure." not in line)
                formatted_output = self.format_output(output)
                self.terminal.insert(tk.END, formatted_output + "\n\n")
        except Exception as e:
            self.terminal.insert(tk.END, str(e))
        self.terminal.config(state=tk.DISABLED)

    def format_output(self, output):
        lines = output.splitlines()
        formatted_output = []
        table_lines = []
        in_table = False

        for line in lines:
            if line.startswith("+") and not in_table:
                in_table = True
                table_lines.append(line)
            elif line.startswith("+") and in_table:
                table_lines.append(line)
                formatted_output.append("\n".join(table_lines))
                table_lines = []
                in_table = False
            elif in_table:
                table_lines.append(line)
            else:
                if table_lines:
                    formatted_output.append("\n".join(table_lines))
                    table_lines = []
                formatted_output.append(line)

        if table_lines:
            formatted_output.append("\n".join(table_lines))

        return "\n".join(formatted_output)

if __name__ == "__main__":
    root = tk.Tk()
    app = MysqlScreen(root, None)
    app.pack(fill="both", expand=True)
    root.mainloop()