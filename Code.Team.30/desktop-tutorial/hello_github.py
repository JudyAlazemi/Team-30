# hello_github.py
# A simple Python program to demonstrate version control with GitHub

def greet_user(name):
    """Return a personalized greeting message."""
    return f"Hello, {name}! Welcome to GitHub."

def main():
    print("🚀 GitHub Demo Program 🚀")
    user_name = input("Enter your name: ")
    message = greet_user(user_name)
    print(message)
    print("✅ You just ran a program tracked by Git and stored on GitHub!")

if __name__ == "__main__":
    main() 
