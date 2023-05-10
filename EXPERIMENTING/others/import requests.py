import requests
import json
import tkinter as tk

# Enter your Transport for NSW API key here
API_KEY = "zZBkkDXyybkIuLAPPW81EuzExQvJuWJ0breL"

# Enter the bus route and stop ID you want to display
BUS_STOP_ID = "209926"

# Define a function to retrieve the next bus arrival times
def get_next_bus_arrivals():
    url = f"https://api.transport.nsw.gov.au/v1/tp/departure_mon?outputFormat=rapidJSON&mode=direct&serviceLineNoticeFilter=3&coordOutputFormat=EPSG%3A4326&departureMonitorMacro=true&name_dm={BUS_STOP_ID}&itdTimeHour=12&itdTimeMinute=00&type_dm=any&nameLine="
    headers = {"Authorization": f"apikey {API_KEY}"}
    response = requests.get(url, headers=headers, verify=False)
    data = json.loads(response.content)
    return data['stopEvents']

# Create a window to display the bus arrival times
window = tk.Tk()
window.title(f"Next buses at stop {BUS_STOP_ID}")

# Create a label to display the bus arrival times
bus_arrivals_label = tk.Label(window, text="Fetching bus arrival times...")
bus_arrivals_label.pack()

# Define a function to update the bus arrival times label
def update_bus_arrivals_label():
    bus_arrivals = get_next_bus_arrivals()
    bus_arrivals_text = ""
    for event in bus_arrivals:
        if 'departureTimePlanned' in event:
            departureTimePlanned = event['departureTimePlanned']
        else:
            departureTimePlanned = "unknown"
        if 'transportation' in event and 'disassembledName' in event['transportation']:
            name = event['transportation']['disassembledName']
        else:
            name = "unknown"
        bus_arrivals_text += f"{name}: {departureTimePlanned}\n"
    bus_arrivals_label.config(text=bus_arrivals_text)
    window.after(10000, update_bus_arrivals_label)  # Update the bus arrival times every 10 seconds


# Start updating the bus arrival times label
update_bus_arrivals_label()

# Run the main event loop for the window
window.mainloop()
